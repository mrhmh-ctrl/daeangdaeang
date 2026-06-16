<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DogDict\Dog;
use App\Models\DogDict\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DogDictController extends Controller
{
	public function index(Request $request)
	{
		$c = $request->get('c');
		$q = $request->get('q'); // ← 이거 추가
		$query = Dog::with('category');

		if ($c) {
			$category = \App\Models\DogDict\Category::where('slug', $c)->first();
			if ($category) {
				$query->where('category_id', $category->id);
			}
		}

		if ($q) {
			$query->where('name', 'like', '%'.$q.'%');
		}

		$dogs = $query->latest()->paginate(20);
		return view('admin.contents.dogdict.index', compact('dogs', 'q'));
		
	}

    public function create()
    {
        $categories = Category::orderBy('sort')->get();
        return view('admin.contents.dogdict.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'content' => 'required']);
        Dog::create($request->only(['category_id', 'name', 'content']));
        return redirect()->route('admin.contents.dogdict.index')->with('success', '등록완료');
    }

    public function edit($id)
    {
        $dog = Dog::findOrFail($id);
        $categories = Category::orderBy('sort')->get();
        return view('admin.contents.dogdict.edit', compact('dog', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $dog = Dog::findOrFail($id);
        $dog->update($request->only(['category_id', 'name', 'content']));
        return redirect()->route('admin.contents.dogdict.index')->with('success', '수정완료');
    }

    public function destroy($id)
    {
        Dog::findOrFail($id)->delete();
        return redirect()->route('admin.contents.dogdict.index')->with('success', '삭제완료');
    }

    public function crawl(Request $request)
    {
        $url = $request->get('url');
        try {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                ->timeout(10)
                ->get($url);

            $html = $response->body();
            preg_match('/<title>(.*?)<\/title>/i', $html, $titleMatch);
            $title = html_entity_decode(strip_tags($titleMatch[1] ?? ''));

            preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $html, $pMatches);
            $content = '';
            $count = 0;
			foreach ($pMatches[1] as $p) {
				if ($count >= 5) break; // 5단락만 가져오기
				$clean = html_entity_decode(strip_tags($p));
				$clean = trim($clean);
				if (strlen($clean) > 20) {
					$content .= '<p>' . $clean . '</p>';
					$count++;
				}
			}

            return response()->json(['success' => true, 'title' => $title, 'content' => trim($content)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function importForm()
    {
        $categories = Category::orderBy('sort')->get();
        return view('admin.contents.dogdict.import', compact('categories'));
    }

    public function fetchBreeds(Request $request)
    {
        try {
            set_time_limit(600);
            $breeds = [];

            $this->fetchCategory('분류:원산지별 개 품종', $breeds);
            $this->fetchCategory('분류:개 품종', $breeds);

            $breeds = array_values(array_unique($breeds));
            sort($breeds);

            return response()->json(['success' => true, 'breeds' => $breeds, 'count' => count($breeds)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function fetchCategory($category, &$breeds, $depth = 0)
    {
        if ($depth > 10) return;

        $cmcontinue = null;

        do {
            $params = [
                'action'  => 'query',
                'list'    => 'categorymembers',
                'cmtitle' => $category,
                'format'  => 'json',
                'cmlimit' => 500,
                'cmtype'  => 'page|subcat',
            ];

            if ($cmcontinue) {
                $params['cmcontinue'] = $cmcontinue;
            }

            $response = Http::withOptions(['verify' => false, 'timeout' => 30])
                ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                ->get('https://ko.wikipedia.org/w/api.php', $params);

            $data    = $response->json();
            $members = $data['query']['categorymembers'] ?? [];

            foreach ($members as $member) {
                if ($member['ns'] == 14) {
                    $this->fetchCategory($member['title'], $breeds, $depth + 1);
                } else {
                    $title = $member['title'];
                    if (strpos($title, '분류:') === false) {
                        $breeds[] = $title;
                    }
                }
            }

            $cmcontinue = $data['continue']['cmcontinue'] ?? null;
            usleep(1000000);

        } while ($cmcontinue);
    }

    public function import(Request $request)
    {
        $title      = $request->get('title');
        $categoryId = $request->get('category_id');

        try {
            if (Dog::where('name', $title)->exists()) {
                return response()->json(['success' => false, 'message' => 'skip: ' . $title]);
            }

            $extract = Http::withOptions(['verify' => false, 'timeout' => 30])
                ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                ->get('https://ko.wikipedia.org/w/api.php', [
                    'action'  => 'query',
                    'titles'  => $title,
                    'prop'    => 'extracts|pageimages',
                    'format'  => 'json',
                    'piprop'  => 'original',
                    'exchars' => 50000,
                ])->json();

            $pages = $extract['query']['pages'] ?? [];
            $page  = reset($pages);

            if (isset($page['missing']) || !isset($page['extract']) || empty(trim($page['extract']))) {

                $langLink = Http::withOptions(['verify' => false, 'timeout' => 30])
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                    ->get('https://ko.wikipedia.org/w/api.php', [
                        'action'  => 'query',
                        'titles'  => $title,
                        'prop'    => 'langlinks',
                        'lllang'  => 'en',
                        'format'  => 'json',
                    ])->json();

                $llPages = $langLink['query']['pages'] ?? [];
                $llPage  = reset($llPages);

                if (empty($llPage['langlinks'][0]['*'])) {
                    $enSearch = Http::withOptions(['verify' => false, 'timeout' => 30])
                        ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                        ->get('https://en.wikipedia.org/w/api.php', [
                            'action'   => 'query',
                            'list'     => 'search',
                            'srsearch' => $title,
                            'format'   => 'json',
                            'srlimit'  => 1,
                        ])->json();
                    $enTitle = $enSearch['query']['search'][0]['title'] ?? $title;
                } else {
                    $enTitle = $llPage['langlinks'][0]['*'];
                }

                $enExtract = Http::withOptions(['verify' => false, 'timeout' => 30])
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                    ->get('https://en.wikipedia.org/w/api.php', [
                        'action'  => 'query',
                        'titles'  => $enTitle,
                        'prop'    => 'extracts|pageimages',
                        'format'  => 'json',
                        'piprop'  => 'original',
                    ])->json();

                $enPages = $enExtract['query']['pages'] ?? [];
                $enPage  = reset($enPages);
                $content = $enPage['extract'] ?? '';
                $image   = $enPage['original']['source'] ?? null;

                if (empty($content)) {
                    return response()->json(['success' => false, 'message' => '내용 없음: ' . $title]);
                }

                $translated = Http::withOptions(['verify' => false, 'timeout' => 30])
                    ->get('https://api.mymemory.translated.net/get', [
                        'q'        => strip_tags(substr($content, 0, 500)),
                        'langpair' => 'en|ko',
                    ])->json();

                $koContent = $translated['responseData']['translatedText'] ?? $content;

                if ($image) {
                    $koContent = '<img src="' . $image . '" style="max-width:400px; float:right; margin:0 0 10px 20px;">' . $koContent;
                }

                Dog::create(['category_id' => $categoryId, 'name' => $title, 'content' => $koContent]);
                return response()->json(['success' => true, 'message' => '영문번역저장: ' . $title]);
            }

            $content = $page['extract'] ?? '';
            $image   = $page['original']['source'] ?? null;

            if ($image) {
                $content = '<img src="' . $image . '" style="max-width:400px; float:right; margin:0 0 10px 20px;">' . $content;
            }

            Dog::create(['category_id' => $categoryId, 'name' => $title, 'content' => $content]);
            return response()->json(['success' => true, 'message' => '저장완료: ' . $title]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function urlImportForm()
    {
        $categories = Category::orderBy('sort')->get();
        return view('admin.contents.dogdict.url_import', compact('categories'));
    }

    public function urlImport(Request $request)
    {
        $url        = $request->get('url');
        $categoryId = $request->get('category_id');
        $name       = $request->get('name');

        try {
            if (Dog::where('name', $name)->exists()) {
                return back()->with('error', '이미 존재하는 견종입니다: ' . $name);
            }

            $response = Http::withOptions(['verify' => false, 'timeout' => 30])
                ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                ->get($url);

            $html = $response->body();

            $image = null;
            if (strpos($url, 'wikipedia.org/wiki/') !== false) {
                $wikiTitle = urldecode(basename(parse_url($url, PHP_URL_PATH)));
                $wikiApi   = Http::withOptions(['verify' => false, 'timeout' => 30])
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                    ->get('https://ko.wikipedia.org/w/api.php', [
                        'action'  => 'query',
                        'titles'  => $wikiTitle,
                        'prop'    => 'pageimages',
                        'format'  => 'json',
                        'piprop'  => 'original',
                    ])->json();

                $wikiPages = $wikiApi['query']['pages'] ?? [];
                $wikiPage  = reset($wikiPages);
                $image     = $wikiPage['original']['source'] ?? null;
            } else {
                preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $html, $imgMatches);
                $imgs = array_filter($imgMatches[1], fn($src) =>
                    (strpos($src, 'http') === 0 || strpos($src, '//') === 0) &&
                    strpos($src, 'icon') === false &&
                    strpos($src, 'logo') === false &&
                    strpos($src, '1px') === false
                );
                $imgs  = array_map(fn($src) => strpos($src, '//') === 0 ? 'https:' . $src : $src, $imgs);
                $image = !empty($imgs) ? reset($imgs) : null;
            }

            preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $html, $pMatches);
            $content = '';

            if ($image) {
                $content .= '<img src="' . $image . '" style="max-width:400px; float:right; margin:0 0 10px 20px;">';
            }

			$count = 0;
			foreach ($pMatches[1] as $p) {
				if ($count >= 5) break;
				$clean = html_entity_decode(strip_tags($p));
				$clean = preg_replace('/\[\d+\]/', '', $clean); // ← 추가
				$clean = trim($clean);
				if (strlen($clean) > 20) {
					$content .= '<p>' . $clean . '</p>';
					$count++;
				}
			}

            if (empty($content)) {
                return back()->with('error', '내용을 가져올 수 없습니다.')->withInput();
            }

            Dog::create([
                'category_id' => $categoryId,
                'name'        => $name,
                'content'     => $content,
            ]);

            return back()->with('success', '저장완료: ' . $name);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
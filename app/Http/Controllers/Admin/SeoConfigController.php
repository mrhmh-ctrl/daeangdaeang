<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeoConfigController extends Controller
{
    public function index()
    {
        $configs = DB::table('seo_config')->get()->keyBy('config_key');
        return view('admin.seo_config', compact('configs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'meta_title'       => 'nullable|max:100',
            'meta_description' => 'nullable|max:500',
            'meta_keywords'    => 'nullable|max:500',
            'og_image'         => 'nullable|max:500',
            'favicon'          => 'nullable|file|mimes:ico,png,jpg,jpeg,svg|max:1024',
        ], [
            'meta_title.max'       => 'SEO 제목은 100자 이내로 입력해주세요.',
            'meta_description.max' => 'SEO 설명은 500자 이내로 입력해주세요.',
            'meta_keywords.max'    => '키워드는 500자 이내로 입력해주세요.',
            'favicon.mimes'        => '파비콘은 ico, png, jpg, svg 형식만 가능합니다.',
            'favicon.max'          => '파비콘 파일은 1MB 이하만 가능합니다.',
        ]);

        // 파비콘 업로드 처리
        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $filename = 'favicon.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $faviconPath = '/images/' . $filename;
        } else {
            $faviconPath = $request->favicon_current;
        }

        $data = [
            'meta_title'       => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords'    => $request->meta_keywords,
            'og_title'         => $request->og_title,
            'og_description'   => $request->og_description,
            'og_image'         => $request->og_image,
            'canonical_url'    => $request->canonical_url,
            'robots'           => $request->robots,
            'favicon'          => $faviconPath,
            'robots_txt'       => $request->robots_txt,
        ];

        foreach ($data as $key => $value) {
            DB::table('seo_config')->where('config_key', $key)->update(['config_value' => $value]);
        }

        return redirect()->route('admin.seo_config.index')
                         ->with('success', 'SEO 설정이 저장되었습니다.');
    }

    public function robotsSync()
    {
    $robots = DB::table('seo_config')
                ->where('config_key', 'robots_txt')
                ->value('config_value');

    file_put_contents(public_path('robots.txt'), $robots);

    return redirect()->route('admin.seo_config.index')
                     ->with('success', 'robots.txt 가 업데이트 되었습니다.');
    }
}
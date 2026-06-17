<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 기본 환경설정
        try {
            $configs = \DB::table('config')
                ->whereIn('config_key', ['site_name', 'site_url'])
                ->pluck('config_value', 'config_key');

            \View::share('site_name', $configs['site_name'] ?? '댕댕닷컴');
            \View::share('site_url',  $configs['site_url']  ?? '');
        } catch (\Exception $e) {
            \View::share('site_name', '댕댕닷컴');
            \View::share('site_url',  '');
        }

        // SEO 설정
        try {
            $seo = \DB::table('seo_config')->get()->keyBy('config_key');

            \View::share('seo_meta_title',       $seo['meta_title']->config_value       ?? '');
            \View::share('seo_meta_description', $seo['meta_description']->config_value ?? '');
            \View::share('seo_meta_keywords',    $seo['meta_keywords']->config_value    ?? '');
            \View::share('seo_og_title',         $seo['og_title']->config_value         ?? '');
            \View::share('seo_og_description',   $seo['og_description']->config_value   ?? '');
            \View::share('seo_og_image',         $seo['og_image']->config_value         ?? '');
            \View::share('seo_canonical_url',    $seo['canonical_url']->config_value    ?? '');
            \View::share('seo_robots',           $seo['robots']->config_value           ?? 'index,follow');
            \View::share('seo_favicon',          $seo['favicon']->config_value          ?? '');  // ← 추가
        } catch (\Exception $e) {
            \View::share('seo_meta_title',       '');
            \View::share('seo_meta_description', '');
            \View::share('seo_meta_keywords',    '');
            \View::share('seo_og_title',         '');
            \View::share('seo_og_description',   '');
            \View::share('seo_og_image',         '');
            \View::share('seo_canonical_url',    '');
            \View::share('seo_robots',           'index,follow');
            \View::share('seo_favicon',          '');  // ← 추가
        }
    }
}
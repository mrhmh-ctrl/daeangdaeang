<!-- head start -->
<meta charset="UTF-8">
<meta name="robots" content="{{ $seo_robots }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="Author" content="댕댕닷컴">
<meta name="description" content="{{ $seo_meta_description }}">
<meta name="keywords" content="{{ $seo_meta_keywords }}">
<link rel="canonical" href="{{ $seo_canonical_url }}">
<title>{{ $seo_meta_title ?: $site_name }}</title>
<!-- favicon -->
<link rel="icon" href="{{ asset('favicon.ico') }}?ver=<?php echo time(); ?>" type="image/x-icon">
<!-- cdn css -->
<link rel="stylesheet" 
  href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/variable/pretendardvariable.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- css -->
<link rel="stylesheet" href="{{ asset('css/companyinfo.css') }}?ver=<?php echo time(); ?>">
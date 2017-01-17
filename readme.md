# naloader tools

小説家になろう！関連サイトからデータを取得するツール。

## 事前準備

* PHP 5.6以降
* composer

## 初期化

    git clone https://github.com/heavymoons/naloader-tools.git
    cd naloader_tools
    composer install
    php artisan migrate

## 小説URLの登録

    php artisan register_novel_url {url}

URLは小説家になろう関連サイトの小説ページURL

## ランキングページなどに含まれる小説URLの登録

    php artisan retrieve_novel_url {url}

## 小説情報の取得／更新

    php artisan crawl_all

## 小説情報のテキストでの出力

    php artisan dump_all {target_dir}

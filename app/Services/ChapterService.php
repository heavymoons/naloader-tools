<?php
namespace App\Services;

use App\Models\Chapter;
use Carbon\Carbon;
use Naloader\Naloader;

/**
 * Class ChapterService
 * @package App\Services
 */
class ChapterService
{
    /**
     * hankaku zenkaku converting options
     */
    const HANKAKU_OPTION_NO_CHANGE = 0;
    const HANKAKU_OPTION_NUMBER_CONVERT_TO_ZENKAKU = 1;
    const HANKAKU_OPTION_ALPHABET_CONVERT_TO_ZENKAKU = 2;
    const HANKAKU_OPTION_NUM_AND_ALPHA_CONVERT_TO_ZENKAKU = 3;

    /**
     * encoding converting options
     */
    const ENCODING_OPTION_UTF8 = 'utf-8';
    const ENCODING_OPTION_UTF16LE = 'unicode';
    const ENCODING_OPTION_SHIFT_JIS = 'shiftjis';
    const ENCODING_OPTION_EUC_JP = 'euc-jp';
    const ENCODING_OPTION_JIS = 'jis';

    /**
     * line breaks converting options
     */
    const LINEBREAK_OPTION_CRLF = 'CRLF';
    const LINEBREAK_OPTION_CR = 'CR';
    const LINEBREAK_OPTION_LF = 'LF';

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getListToCrawl() {
        return Chapter::whereRaw('crawled_at is null or (updated_on is not null and crawled_at is not null and updated_on > crawled_at)')->get();
    }

    /**
     * download text
     * @param Chapter $chapter
     * @param string $encodingOption
     * @param int $hankakuOption
     * @param string $linebreakOption
     * @return string
     */
    public static function download(
        Chapter $chapter,
        $encodingOption = Naloader::ENCODING_OPTION_UTF8,
        $hankakuOption = Naloader::HANKAKU_OPTION_NO_CHANGE,
        $linebreakOption = Naloader::LINEBREAK_OPTION_CRLF
    ) {
        $downloadUrl = $chapter->novel->text_download_url;
        $params = [
            'no' => $chapter->number,
            'code' => $encodingOption,
            'hankaku' => $hankakuOption,
            'kaigyo' => $linebreakOption,
        ];
        $url = $downloadUrl . '?' . http_build_query($params);
        $text = file_get_contents($url);

        $chapter->content = $text;
        $chapter->crawled_at = Carbon::now();
        $chapter->save();
    }
}
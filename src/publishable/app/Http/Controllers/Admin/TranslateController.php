<?php

namespace App\Http\Controllers\Admin;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use TCG\Voyager\Http\Controllers\Controller as VoyagerController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Models\FileTranslation;

/**
 * Controller for translane language files.
 */
class TranslateController extends VoyagerController
{
    /**
     * Display page with list language files.
     *
     * @param \Illuminate\Http\Request $request User request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $languages = $this->getLangs();
        $langFiles = $this->getListLangFiles();
        $this->createNewLangFiles($langFiles, $languages);
        $langFiles = $this->sortFilesByLang($langFiles, $languages);

        return view('admin.translate.index', compact('languages', 'langFiles'));
    }

    /**
     * Display page for edit some language file.
     *
     * @param \Illuminate\Http\Request $request User request
     * @param string $cryptFileName Crypt language file path
     * @return \Illuminate\View\View
     */
    public function editLangFile(Request $request, $cryptFileName)
    {
        $fileName = Crypt::decryptString($cryptFileName);

        if (!file_exists($fileName)) {
            abort(404);
        }

        if (!$request->session()->exists('content')) {

            $content = include($fileName);

        } else {
            $content = $request->session()->get('content');
            $request->session()->forget('content');
        }

        $formElements = $this->flattenKeysRecursively($content);

        return view('admin.translate.edit', compact('formElements', 'cryptFileName'));
    }

    /**
     * Update language file.
     *
     * @param \Illuminate\Http\Request $request User request
     * @param string $cryptFileName Crypt language file path
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateLangFile(Request $request, $cryptFileName)
    {
        $fileName = Crypt::decryptString($cryptFileName);

        if (!file_exists($fileName)) {
            abort(404);
        }

        $content = include($fileName);
        $formElements = $this->flattenKeysRecursively($content);

        $data = $request->except(['_token', '_method']);

        file_put_contents($fileName, "<?php\nreturn " . var_export($data, true) . "\n?>");

        $request->session()->put('content', $data);

        return redirect()->route('lang.file.form', ['cryptFileName' => $cryptFileName]);
    }

    /**
     * Add keys to language file.
     *
     * @param \Illuminate\Http\Request $request User request
     * @param string $cryptFileName Crypt language file path
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addKeysToLangFiles(Request $request, $cryptFileName)
    {
        $fileName = Crypt::decryptString($cryptFileName);

        if (!file_exists($fileName)) {
            abort(404);
        }

        $response = [
            'status' => false,
            'html' => '',
            'errors' => [],
        ];

        $keys = $request->key;
        $values = $request->value;

        $content = include($fileName);

        $errors = [];

        if (empty($keys) || empty($values)) {
            return response()->json($response);
        }

        foreach ($keys as $index => $keyName) {
            if (array_key_exists($keyName, $content)) {
                $errors[] = [
                    'nameInput' => 'key[' . $index . ']',
                    'textError' => 'Такой ключ уже существует в файле перевода',
                ];
            }
            if (empty($keyName)) {
                $errors[] = [
                    'nameInput' => 'key[' . $index . ']',
                    'textError' => 'Ключ не может быть пустым',
                ];
            }
        }

        if ($errors) {
            $response[ 'errors' ] = $errors;
        } else {
            $this->addKeys($keys, $values, $fileName);

            $response[ 'status' ] = true;

            foreach (array_combine($keys, $values) as $key => $value) {
                $response[ 'html' ] .= sprintf('<div class="form-group"><label>%s</label><textarea class="form-control" name="%s">%s</textarea></div>', $key, $key, $value);
            }
        }

        return response()->json($response);
    }

    /**
     * Upgrade language files.
     * Check changes language files. If file will be changed then add change to other lang files,
     *
     * @param \Illuminate\Http\Request $request User request
     * @return \Illuminate\View\View
     */
    public function upgradeLangFiles(Request $request)
    {
        $languages = $this->getLangs();
        $langFiles = $this->getListLangFiles();

        foreach ($langFiles as $file) {
            $currentLang = preg_replace('#^.*?resources/lang/([a-z]{2}).*$#', '$1', $file);
            $content = include($file);

            $otherLangFiles = [];

            foreach ($languages as $lang) {
                if ($lang === $currentLang) {
                    continue;
                }

                $filePath = preg_replace('#^(.*?)resources/lang/[a-z]{2}(.*)$#', '$1resources/lang/' . $lang . '$2', $file);
                $otherContent = include($filePath);

                $otherLangFiles[ $lang ] = [
                    'path' => $filePath,
                    'content' => $otherContent,
                ];
            }

            foreach ($otherLangFiles as $otherLangFile) {
                $md5ContentOtherFile = md5(json_encode($otherLangFile[ 'content' ]));

                foreach ($content as $key => $value) {
                    if (!array_key_exists($key, $otherLangFile[ 'content' ])) {
                        $otherLangFile[ 'content' ][ (string)$key ] = $value;
                    }
                }

                $newMd5 = md5(json_encode($otherLangFile[ 'content' ]));

                if ($md5ContentOtherFile !== $newMd5) {
                    file_put_contents($otherLangFile[ 'path' ], "<?php\nreturn " . var_export($otherLangFile[ 'content' ], true) . "\n?>");
                }
            }
        }

        return redirect()->route('lang.file.index');
    }

    /**
     * Export language files content to database.
     *
     * @param \Illuminate\Http\Request $request User request
     * @return \Illuminate\View\View
     */
    public function exportToDb(Request $request)
    {
        FileTranslation::truncate();
        $langFiles = $this->getListLangFiles();

        foreach ($langFiles as $file) {

            $this->insertTranslationToDb($file);
        }

        return redirect()->route('lang.file.index');
    }

    /**
     * Update language files content in database.
     *
     * @param \Illuminate\Http\Request $request User request
     * @return \Illuminate\View\View
     */
    public function updateTranslationInDb(Request $request)
    {
        $languages = $this->getLangs();
        $langFiles = $this->getListLangFiles();
        $langFiles = $this->sortFilesByLang($langFiles, $languages);

        foreach ($langFiles as $lang => $files) {
            $translations = FileTranslation::where('lang', $lang)
                ->get()
                ->keyBy('md5');

            $this->updateTranslations($files, $lang);
        }

        return redirect()->route('lang.file.index');
    }

    /**
     * Import language files content from database.
     *
     * @param \Illuminate\Http\Request $request User request
     * @return \Illuminate\View\View
     */
    public function importFromDb(Request $request)
    {
        $translations = FileTranslation::get();

        $langPath = app()->langPath();

        foreach ($translations as $translation) {
            $filePath = $langPath . '/' . $translation->lang . '/' . $translation->file_path;
            $directory = dirname($filePath);

            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            file_put_contents($filePath, "<?php\nreturn " . var_export(json_decode($translation->content, true), true) . "\n?>");
        }

        return redirect()->route('lang.file.index');
    }

    /**
     * Insert language file to database.
     *
     * @access private
     * @param string $file File path.
     * @return void
     */
    private function insertTranslationToDb($file)
    {
        $fileContent = include($file);

        preg_match_all('#.*?/lang/(.*?)/(.*)$#', $file, $matches);

        if (!isset($matches[ 1 ][ 0 ], $matches[ 2 ][ 0 ])) {
            return;
        }

        $lang = $matches[ 1 ][ 0 ];
        $filePath = $matches[ 2 ][ 0 ];

        $content = json_encode($fileContent);

        FileTranslation::create([
            'lang' => $lang,
            'content' => $content,
            'md5' => md5($lang . $filePath . $content),
            'file_path' => $filePath,
        ]);
    }

    /**
     * Update language file in database.
     * Update exist language translation or create new.
     *
     * @access private
     * @param array $files Files groups by language @see function sortFilesByLang.
     * @param string $lang Language.
     * @return void
     */
    private function updateTranslations($files, $lang)
    {
        foreach ($files as $itemFile) {
            $fileContent = include($itemFile[ 'path' ]);
            $filePath = str_replace($lang . '/', '', $itemFile[ 'uri' ]);

            $jsonContent = json_encode($fileContent);
            $md5 = md5($lang . $filePath . $jsonContent);

            if (isset($translations[ $md5 ])) {
                continue;
            }

            FileTranslation::updateOrCreate([
                'lang'      => $lang,
                'file_path' => $filePath,
            ], [
                'content' => $jsonContent,
                'md5'     => $md5,
            ]);
        }
    }

    /**
     * Add new keys to lang files.
     *
     * @access private
     * @param array $keys Keys.
     * @param array $values Values
     * @param string $fileName File name
     * @return void
     */
    private function addKeys($keys, $values, $fileName)
    {
        $languages = $this->getLangs();

        foreach ($languages as $language) {
            $filePath = preg_replace('#^(.*?)resources/lang/[a-z]{2}(.*)$#', '$1resources/lang/' . $language . '$2', $fileName);

            $content = include($filePath);

            $md5Content = md5(json_encode($content));

            foreach ($keys as $index => $nameKey) {
                if (!array_key_exists($nameKey, $content)) {
                    $content[ $nameKey ] = (isset($values[ $index ])) ? $values[ $index ] : '';
                }
            }

            $newMd5Content = md5(json_encode($content));

            if ($md5Content !== $newMd5Content) {
                file_put_contents($filePath, "<?php\nreturn " . var_export($content, true) . "\n?>");
            }
        }
    }

    /**
     * Get all language files for project.
     *
     * @access private
     * @return array
     */
    private function getListLangFiles()
    {
        $langPath = resource_path() . DIRECTORY_SEPARATOR . 'lang';
        $files = [];
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($langPath));

        foreach ($rii as $file) {
            if ($file->isDir()){
                continue;
            }

            $files[] = $file->getPathname();
        }

        return $files;
    }

    /**
     * Returns maps of the flatten keys with corresponding values.
     *
     * @access private
     * @param array $array
     * @return array
     */
    private function flattenKeysRecursively(array $array)
    {
        $result = [];
        $this->flattenKeyRecursively($array, $result, '');

        $newResult = [];

        foreach ($result as $key => $value) {
            if (!strpos($key, '.')) {
                $newResult[ $key ] = $value;
                continue;
            }

            $dataKey = explode('.', $key);

            $itemKey = array_shift($dataKey);

            foreach ($dataKey as $item) {
                $itemKey .= '[' . $item . ']';
            }

            $newResult[ $itemKey ] = $value;
        }

        return $newResult;
    }

    /**
     * Flatten key recursive.
     *
     * @access private
     * @param array $array Array structure
     * @param array $result Result
     * @param string $parentKey Parent key
     * @return void
     */
    private function flattenKeyRecursively($array, &$result, $parentKey)
    {
        foreach ($array as $key => $value) {
            $itemKey = ($parentKey ? $parentKey . '.' : '') . $key;
            if (is_array($value)) {
                call_user_func_array([$this, __METHOD__], [$value, &$result, $itemKey]);
            } else {
                $result[$itemKey] = $value;
            }
        }
    }

    /**
     * Get list languages for site.
     *
     * @access private
     * @return array
     */
    private function getLangs()
    {
        return config('voyager.multilingual.locales', []);
    }

    /**
     * Create new language files if file exist in some language.
     *
     * @access private
     * @param array $files List file paths.
     * @param array $langs List languages
     * @return void
     */
    private function createNewLangFiles(&$files, $langs)
    {
        foreach ($files as $fileName) {
            foreach ($langs as $lang) {
                $tmpFileName = preg_replace('#^(.*?)resources/lang/[a-z]{2}(.*)$#', '$1resources/lang/' . $lang . '$2', $fileName);

                if (file_exists($tmpFileName)) {
                    continue;
                }

                $directory = dirname($tmpFileName);

                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                copy($fileName, $tmpFileName);
                $files[] = $tmpFileName;
            }
        }
    }

    /**
     * Sorting language files by language.
     *
     * @access private
     * @param array $files List file paths.
     * @param array $langs List languages
     * @return array
     */
    private function sortFilesByLang($files, $langs)
    {
        $langFiles = [];

        foreach ($files as $fileName) {
            foreach ($langs as $lang) {
                if (strpos($fileName, '/lang/' . $lang . '/')) {
                    $langFiles[ $lang ][] = [
                        'path' => $fileName,
                        'code' => Crypt::encryptString($fileName),
                        'uri' => preg_replace('#^.*?/lang/#', '', $fileName),
                    ];
                }
            }
        }

        return $langFiles;
    }
}

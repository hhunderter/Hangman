<?php

/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\Hangman\Libs;

class Templates
{
    private $translator;

    private $parts = [];
    private $keys = [];
    private $lists = [];
    private $url = '';

    /**
     * @return \Ilch\Translator
     */
    private function getTranslator(): \Ilch\Translator
    {
        return $this->translator;
    }

    /**
     * @param \Ilch\Translator $translator
     * @return $this
     */
    private function setTranslator(\Ilch\Translator $translator): Templates
    {
        $this->translator = $translator;
        return $this;
    }

    /**
     * @param int|null $key
     * @return string|null
     */
    private function getPart(?int $key = null): ?string
    {
        return $this->parts[$key] ?? null;
    }

    /**
     * @return array
     */
    private function getParts(): array
    {
        return $this->parts;
    }

    /**
     * @param array $parts
     * @return $this
     */
    private function setParts(array $parts): Templates
    {
        $this->parts = $parts;
        return $this;
    }

    /**
     * @param string $part
     * @param int|string|null $key
     * @return $this
     */
    private function addPart(string $part, $key = null): Templates
    {
        if ($key) {
            $this->parts[$key] = $part;
        } else {
            $this->parts[] = $part;
        }
        return $this;
    }

    /**
     * @param int|string|null $key
     * @return $this
     */
    private function delPart($key = null): Templates
    {
        if ($key) {
            if ($this->getPart($key)) {
                unset($this->parts[$key]);
            }
        } else {
            $this->setParts([]);
        }
        return $this;
    }

    /**
     * @param int|string|null $key
     * @return string|null
     */
    private function getKey($key = null): ?string
    {
        return $this->keys[$key] ?? null;
    }

    /**
     * @return array
     */
    private function getKeys(): array
    {
        return $this->keys;
    }

    /**
     * @param array $keys
     * @return $this
     */
    private function setKeys(array $keys): Templates
    {
        $this->keys = $keys;
        return $this;
    }

    /**
     * @param string $keys
     * @param int|string|null $key
     * @return $this
     */
    private function addKey(string $keys, $key = null): Templates
    {
        if ($key) {
            $this->keys[$key] = $keys;
        } else {
            $this->keys[] = $keys;
        }
        return $this;
    }

    /**
     * @param int|string|null $key
     * @return $this
     */
    private function delKey($key = null): Templates
    {
        if ($key) {
            if ($this->getKey($key)) {
                unset($this->keys[$key]);
            }
        } else {
            $this->setKeys([]);
        }
        return $this;
    }

    /**
     * @param int|string|null $key
     * @return string|null
     */
    private function getList($key = null): ?string
    {
        return $this->lists[$key] ?? null;
    }

    /**
     * @return array
     */
    private function getLists(): array
    {
        return $this->lists;
    }

    /**
     * @param array $Lists
     * @return $this
     */
    private function setLists(array $Lists): Templates
    {
        $this->lists = $Lists;
        return $this;
    }

    /**
     * @param string $List
     * @param int|string|null $key
     * @return $this
     */
    private function addList(string $List, $key = null): Templates
    {
        if ($key) {
            $this->lists[$key] = $List;
        } else {
            $this->lists[] = $List;
        }
        return $this;
    }

    /**
     * @param int|string|null $key
     * @return $this
     */
    private function delList($key = null): Templates
    {
        if ($key) {
            if ($this->getList($key)) {
                unset($this->lists[$key]);
            }
        } else {
            $this->setLists([]);
        }
        return $this;
    }

    /**
     * @return string
     */
    private function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    private function setUrl(string $url = ''): Templates
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param \Ilch\Translator $translator
     * @param string $file
     * @param string $ort
     */
    public function __construct(\Ilch\Translator $translator, string $file, string $ort = '')
    {
        $this->setTranslator($translator);

        $this->setUrl($ort);

        // file bearbeiten, weil file auch ohne .htm angegeben werden kann.
        if (($this->getUrl() !== 'FILE') and (substr($file, -4) != '.htm')) {
            $file .= '.htm';
        }
        $inhalt = '';

        if ($this->getUrl() === 'FILE') {
            $inhalt = $file;
        } elseif ($this->getUrl()) {
            $file = $this->getUrl() . '/' . $file;
        }

        if ($this->getUrl() !== 'FILE') {
            $inhalt = file_get_contents($file);
        }

        $inhalt = $this->replaceLang($inhalt);

        $inhalt = $this->replaceList($inhalt);
        $this->setParts(explode('{EXPLODE}', $inhalt) ?? []);
    }

    /**
     * @param null|array|numeric $parts
     * @return bool
     */
    public function removeParts($parts = null): bool
    {
        if (!is_null($parts)) {
            if (is_array($parts)) {
                foreach ($parts as $key) {
                    if (is_numeric($key)) {
                        $this->addPart('', $key);
                    }
                }
                return true;
            } else {
                if (is_numeric($parts)) {
                    $this->addPart('', $parts);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param string $var
     * @return string
     */
    private function replaceLang(string $var): string
    {
        $lang_zwischenspeicher = [];
        preg_match_all("/\{_lang_([^{}]+)}/", $var, $lang_zwischenspeicher);
        foreach ($lang_zwischenspeicher[1] as $v) {
            $var = str_replace('{_lang_' . $v . '}', $this->getTranslator()->trans($v), $var);
        }
        return ($var);
    }

    /**
     * @param string $var
     * @return string
     */
    private function replaceList(string $var): string
    {
        $zwischenspeicher = [];
        preg_match_all("/\{_list_([^{}]+)}/", $var, $zwischenspeicher);
        foreach ($zwischenspeicher[1] as $v) {
            list($key, $val) = explode('@', $v);
            $this->addList($val, $key);
            $var = str_replace('{_list_' . $v . '}', '{' . $key . '}', $var);
        }
        return ($var);
    }

    /**
     * @param string $key
     * @param array $ar
     * @return string
     */
    public function listGet(string $key, array $ar): string
    {
        $zwischenspeicher = $this->getList($key);
        krsort($ar);
        foreach ($ar as $k => $v) {
            $i = is_int($k) ? $k + 1 : $k . '%';
            $zwischenspeicher = str_replace('%' . $i, $v, $zwischenspeicher);
        }
        return ($zwischenspeicher);
    }

    /**
     * @param string $key
     * @param array $ar
     * @return $this
     */
    public function listSetAr(string $key, array $ar): Templates
    {
        $listString = '';
        foreach ($ar as $listEntry) {
            if (!is_array($listEntry)) {
                $listEntry = array($listEntry);
            }
            $listString .= $this->listGet($key, $listEntry);
        }
        $this->set($key, $listString);
        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function listExists(string $key): bool
    {
        return (bool)$this->getList($key);
    }

    /**
     * @param string $key
     * @param array $ar
     * @param bool $print
     * @return string
     */
    public function listOut(string $key, array $ar, bool $print = true): string
    {
        $return = $this->listGet($key, $ar);
        if ($print) {
            echo $return;
        }
        return $return;
    }

    /**
     * @param string $k
     * @param string $v
     * @return $this
     */
    public function set(string $k, string $v): Templates
    {
        $this->addKey($v, $k);
        return $this;
    }

    /**
     * @param array $ar
     * @return $this
     */
    public function setAr(array $ar): Templates
    {
        foreach ($ar as $k => $v) {
            $this->set($k, $v);
        }
        return $this;
    }

    /**
     * @param array $ar
     * @param int $pos
     * @param bool $print
     * @return string
     */
    public function setArOut(array $ar, int $pos, bool $print = true): string
    {
        $this->setAr($ar);
        $return = $this->out($pos, false);
        if ($print) {
            echo $return;
        }
        return $return;
    }

    public function setOut($k, $v, int $pos, bool $print = true): string
    {
        $this->set($k, $v);
        $return = $this->out($pos, false);
        if ($print) {
            echo $return;
        }
        return $return;
    }

    public function setArGet(array $ar, int $pos): string
    {
        $this->setAr($ar);
        return ($this->get($pos));
    }

    /**
     * @param string $k
     * @param string $v
     * @param int $pos
     * @return string
     */
    public function setGet(string $k, string $v, int $pos): string
    {
        $this->set($k, $v);
        return ($this->get($pos));
    }

    /**
     * @param string $k
     * @return bool
     */
    public function del(string $k): bool
    {
        if ($this->getKey($k)) {
            $this->delKey($k);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $ar
     * @return bool
     */
    public function delAr(array $ar): bool
    {
        foreach ($ar as $k => $v) {
            $this->del($k);
        }
        return true;
    }

    /**
     * @param array $tr
     * @return string
     */
    private function parseIfDo(array $tr): string
    {
        if ($tr[1] == 'SESSION_USERID') {
            $this->addKey($_SESSION['user_id'], $tr[1]);
        }
        $tr1 = $this->getKey($tr[1]);
        if (
            $tr1
            and (
                ($tr[2] == '==' and $tr1 == $tr[3])
                or (($tr[2] == '!=' or $tr[2] == '<>') and $tr1 != $tr[3])
                or ($tr[2] == '<=' and $tr1 <= $tr[3])
                or ($tr[2] == '>=' and $tr1 >= $tr[3])
                or ($tr[2] == '<' and $tr1 < $tr[3])
                or ($tr[2] == '>' and $tr1 > $tr[3])
            )
        ) {
            return ($tr[4]);
        } elseif ($tr1 and isset($tr[6])) {
            return ($tr[6]);
        }
        return ('');
    }

    /**
     * @param int $pos
     * @return string
     */
    private function parseIf(int $pos): string
    {
        $toout = $this->getPart($pos);

        $toout = preg_replace_callback("#\{_if_\{([^}]+)}(==|!=|<>|<|>|<=|>=)'([^']*)'}(.*)(\{_else_}(.*))?\{/_endif}#Us", array($this, 'parseIfDo'), $toout);

        return ($toout);
    }

    /**
     * @param int $pos
     * @return string
     */
    public function get(int $pos): string
    {
        $toout = $this->parseIf($pos);

        mt_srand((int)microtime() * 1000000);
        $z = '##@@' . mt_rand() . '@@##';

        foreach ($this->getKeys() as $k => $v) {
            $toout = str_replace('{' . $k . '}', '{' . $z . $k . '}', $toout);
        }

        foreach ($this->getKeys() as $k => $v) {
            $toout = str_replace('{' . $z . $k . '}', $v, $toout);
        }
        return $toout;
    }

    /**
     * @param int $pos
     * @param bool $print
     * @return string
     */
    public function out(int $pos, bool $print = true): string
    {
        $return = $this->get($pos);
        if ($print) {
            echo $return;
        }
        return $return;
    }
}

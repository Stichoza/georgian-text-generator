<?php class WordGenerator {
    private $punct_any = Array(",", ",", ",", ",", ",", ",", " -", "?", "!", "?!");
    private $punct_end = Array(".", ".", ".", ".", ".", ".", "...", "?", "!", "?!");
    private $vowels = Array("ა", "ე", "ი", "ო", "უ");
    private $meore = Array("ბ", "გ", "დ", "ვ", "ზ", "თ",
        "კ", "ლ", "მ", "ნ", "პ", "ჟ", "რ", "ს", "ტ", "ფ", "ქ",
        "ღ", "ყ", "შ", "ჩ", "ც", "ძ", "წ", "ჭ", "ხ", "ჯ", "ჰ");
    private $endings = Array("მა", "ით", "ქნა", "ები", "ოვა");
    private $prefixes = Array("გადა", "ჩა", "მიმო", "წა", "შე", "გაა");
    public function generateWord($length, $strict = false) {
        $offset = ($strict) ? 0 : rand(0, 1);
        if (($offset && $length%2!=0) || (!$offset && $length%2==0)) $length++;
        for ($i=0; $i<$length; $i++) {
            $letters = (($i+$offset)%2 == 0) ? $this->vowels : $this->meore;
            $word .= $letters[rand(0, count($letters)-1)];
        }
        if (!rand(0, 4)) $word .= $this->endings[rand(0, count($this->endings)-1)];
        if (!rand(0, 4)) $word = $this->prefixes[rand(0, count($this->prefixes)-1)] . $word;
        return $word;
    }
    public function generateSentence($n) {
        for ($i=0; $i<$n; $i++) {
            $sentence .= $this->generateWord(rand(3, 8));
            if ($i == $n-1) {
                $sentence .= $this->punct_end[rand(0, count($this->punct_end)-1)];
                break;
            } elseif (!rand(0,4)) {
                $sentence .= $this->punct_any[rand(0, count($this->punct_any)-1)];
            }
            $sentence .= " ";
        }
        return $sentence;
    }
}
?>

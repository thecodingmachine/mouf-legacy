<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

?>
<h1>Supported languages</h1>

<p>You application supports these languages: <?php 
$languageList = "<em>".implode("</em>, <em>", $this->languages)."</em>";
echo $languageList;
?>
</p>

<form action="addSupportedLanguage">
<p>Add another language:
<select name="language">
<option value='aa'>Afar</option>
<option value='ab'>Abkhazian</option>
<option value='ae'>Avestan</option>
<option value='af'>Afrikaans</option>
<option value='ak'>Akan</option>
<option value='am'>Amharic</option>
<option value='an'>Aragonese</option>
<option value='ar'>Arabic</option>
<option value='as'>Assamese</option>
<option value='av'>Avaric</option>
<option value='ay'>Aymara</option>
<option value='az'>Azerbaijani</option>
<option value='ba'>Bashkir</option>
<option value='be'>Belarusian</option>
<option value='bg'>Bulgarian</option>
<option value='bh'>Bihari</option>
<option value='bi'>Bislama</option>
<option value='bm'>Bambara</option>
<option value='bn'>Bengali</option>
<option value='bo'>Tibetan</option>
<option value='br'>Breton</option>
<option value='bs'>Bosnian</option>
<option value='ca'>Catalan, Valencian</option>
<option value='ce'>Chechen</option>
<option value='ch'>Chamorro</option>
<option value='co'>Corsican</option>
<option value='cr'>Cree</option>
<option value='cs'>Czech</option>
<option value='cu'>Church Slavic, Old Slavonic, Church Slavonic, Old Bulgarian, Old Church Slavonic</option>
<option value='cv'>Chuvash</option>
<option value='cy'>Welsh</option>
<option value='da'>Danish</option>
<option value='de'>German</option>
<option value='dv'>Divehi, Dhivehi, Maldivian</option>
<option value='dz'>Dzongkha</option>
<option value='ee'>Ewe</option>
<option value='el'>Modern Greek</option>
<option value='en'>English</option>
<option value='eo'>Esperanto</option>
<option value='es'>Spanish, Castilian</option>
<option value='et'>Estonian</option>
<option value='eu'>Basque</option>
<option value='fa'>Persian</option>
<option value='ff'>Fulah</option>
<option value='fi'>Finnish</option>
<option value='fj'>Fijian</option>
<option value='fo'>Faroese</option>
<option value='fr'>French</option>
<option value='fy'>Western Frisian</option>
<option value='ga'>Irish</option>
<option value='gd'>Gaelic, Scottish Gaelic</option>
<option value='gl'>Galician</option>
<option value='gn'>Guaraní</option>
<option value='gu'>Gujarati</option>
<option value='gv'>Manx</option>
<option value='ha'>Hausa</option>
<option value='he'>Modern Hebrew</option>
<option value='hi'>Hindi</option>
<option value='ho'>Hiri Motu</option>
<option value='hr'>Croatian</option>
<option value='ht'>Haitian, Haitian Creole</option>
<option value='hu'>Hungarian</option>
<option value='hy'>Armenian</option>
<option value='hz'>Herero</option>
<option value='ia'>Interlingua (International Auxiliary Language Association)</option>
<option value='id'>Indonesian</option>
<option value='ie'>Interlingue, Occidental</option>
<option value='ig'>Igbo</option>
<option value='ii'>Sichuan Yi, Nuosu</option>
<option value='ik'>Inupiaq</option>
<option value='io'>Ido</option>
<option value='is'>Icelandic</option>
<option value='it'>Italian</option>
<option value='iu'>Inuktitut</option>
<option value='ja'>Japanese</option>
<option value='jv'>Javanese</option>
<option value='ka'>Georgian</option>
<option value='kg'>Kongo</option>
<option value='ki'>Kikuyu, Gikuyu</option>
<option value='kj'>Kwanyama, Kuanyama</option>
<option value='kk'>Kazakh</option>
<option value='kl'>Kalaallisut, Greenlandic</option>
<option value='km'>Central Khmer</option>
<option value='kn'>Kannada</option>
<option value='ko'>Korean</option>
<option value='kr'>Kanuri</option>
<option value='ks'>Kashmiri</option>
<option value='ku'>Kurdish</option>
<option value='kv'>Komi</option>
<option value='kw'>Cornish</option>
<option value='ky'>Kirghiz, Kyrgyz</option>
<option value='la'>Latin</option>
<option value='lb'>Luxembourgish, Letzeburgesch</option>
<option value='lg'>Ganda</option>
<option value='li'>Limburgish, Limburgan, Limburger</option>
<option value='ln'>Lingala</option>
<option value='lo'>Lao</option>
<option value='lt'>Lithuanian</option>
<option value='lu'>Luba-Katanga</option>
<option value='lv'>Latvian</option>
<option value='mg'>Malagasy</option>
<option value='mh'>Marshallese</option>
<option value='mi'>Māori</option>
<option value='mk'>Macedonian</option>
<option value='ml'>Malayalam</option>
<option value='mn'>Mongolian</option>
<option value='mr'>Marathi</option>
<option value='ms'>Malay</option>
<option value='mt'>Maltese</option>
<option value='my'>Burmese</option>
<option value='na'>Nauru</option>
<option value='nb'>Norwegian Bokmål</option>
<option value='nd'>North Ndebele</option>
<option value='ne'>Nepali</option>
<option value='ng'>Ndonga</option>
<option value='nl'>Dutch, Flemish</option>
<option value='nn'>Norwegian Nynorsk</option>
<option value='no'>Norwegian</option>
<option value='nr'>South Ndebele</option>
<option value='nv'>Navajo, Navaho</option>
<option value='ny'>Chichewa, Chewa, Nyanja</option>
<option value='oc'>Occitan (after 1500)</option>
<option value='oj'>Ojibwa</option>
<option value='om'>Oromo</option>
<option value='or'>Oriya</option>
<option value='os'>Ossetian, Ossetic</option>
<option value='pa'>Panjabi, Punjabi</option>
<option value='pi'>Pāli</option>
<option value='pl'>Polish</option>
<option value='ps'>Pashto, Pushto</option>
<option value='pt'>Portuguese</option>
<option value='qu'>Quechua</option>
<option value='rm'>Romansh</option>
<option value='rn'>Rundi</option>
<option value='ro'>Romanian, Moldavian, Moldovan</option>
<option value='ru'>Russian</option>
<option value='rw'>Kinyarwanda</option>
<option value='sa'>Sanskrit</option>
<option value='sc'>Sardinian</option>
<option value='sd'>Sindhi</option>
<option value='se'>Northern Sami</option>
<option value='sg'>Sango</option>
<option value='si'>Sinhala, Sinhalese</option>
<option value='sk'>Slovak</option>
<option value='sl'>Slovenian</option>
<option value='sm'>Samoan</option>
<option value='sn'>Shona</option>
<option value='so'>Somali</option>
<option value='sq'>Albanian</option>
<option value='sr'>Serbian</option>
<option value='ss'>Swati</option>
<option value='st'>Southern Sotho</option>
<option value='su'>Sundanese</option>
<option value='sv'>Swedish</option>
<option value='sw'>Swahili</option>
<option value='ta'>Tamil</option>
<option value='te'>Telugu</option>
<option value='tg'>Tajik</option>
<option value='th'>Thai</option>
<option value='ti'>Tigrinya</option>
<option value='tk'>Turkmen</option>
<option value='tl'>Tagalog</option>
<option value='tn'>Tswana</option>
<option value='to'>Tonga (Tonga Islands)</option>
<option value='tr'>Turkish</option>
<option value='ts'>Tsonga</option>
<option value='tt'>Tatar</option>
<option value='tw'>Twi</option>
<option value='ty'>Tahitian</option>
<option value='ug'>Uighur, Uyghur</option>
<option value='uk'>Ukrainian</option>
<option value='ur'>Urdu</option>
<option value='uz'>Uzbek</option>
<option value='ve'>Venda</option>
<option value='vi'>Vietnamese</option>
<option value='vo'>Volapük</option>
<option value='wa'>Walloon</option>
<option value='wo'>Wolof</option>
<option value='xh'>Xhosa</option>
<option value='yi'>Yiddish</option>
<option value='yo'>Yoruba</option>
<option value='za'>Zhuang, Chuang</option>
<option value='zh'>Chinese</option>
<option value='zu'>Zulu</option>

</select>
</p>
<button type="submit">Add language</button>
</form>
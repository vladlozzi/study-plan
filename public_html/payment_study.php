<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль payment_study.php</p>"; 
                            require "footer.php"; exit(); }
$credit = 0; ?>
Річна плата за навчання відповідно до договору становить <? echo "...";?> грн.<br>
Загальна нарахована Вам сума плати за навчання становить <? echo "...";?> грн.<br>
Станом на <? echo bold(date('d.m.Y')."р."); ?> Ви сплатили <? echo "...";?> грн. &nbsp; &nbsp; 
<span style="font-size: 133%; font-weight: bold;">
<? echo ($credit == 0) ? "Боргу немає" : "Борг становить ".$credit." грн." ;?>
</span>

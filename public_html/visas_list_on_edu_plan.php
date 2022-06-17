<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль visas_list_on_edu_plan.php</p>"; 
									require "footer.php"; exit(); }
// Перелік віз для робочого навчального плану
?>
<tr><td colspan=8 style="text-align: left;">
			<p style="position: relative; left: 10px; margin-top: 0em; margin-bottom: 0em;
								font-weight: bold;"><?php 
					if (($query_row['sem_start_current'] > 0) and ($query_row['sem_final_current'] > 0)) { ?>
				<span style="color: DarkGreen;">Чинний</span><?php }
					else  { ?>
				<span style="color: Red;">Не чинний</span><?php }	?>. Підписано: <?php
					if (!empty($query_row['proxy_signature'])) echo "уповноваженим (".
							substr($query_row['proxy_signature'], -10).")"; 
					if (!empty($query_row['depart_head_visa'])) echo ", зав. кафедри (".
							substr($query_row['depart_head_visa'], -10).")"; 
					if (!empty($query_row['dekan_visa'])) echo ", директором інституту (".
							substr($query_row['dekan_visa'], -10).")"; 
					if (!empty($query_row['methodist_visa'])) echo ", методистом навч. відділу (".
							substr($query_row['methodist_visa'], -10).")"; 
					if (!empty($query_row['study_depart_boss_visa'])) echo ", начальником навч. відділу (".
							substr($query_row['study_depart_boss_visa'], -10).")"; 
					if (!empty($query_row['vicerector_visa'])) echo ", проректором з НПР (".
							substr($query_row['vicerector_visa'], -10).")"; 
				?>
			</p>
		</td>
</tr>

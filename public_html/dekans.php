<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль dekans.php</p>"; require "footer.php"; exit(); }
?><br>
<table style="margin-left: 0%; width: 85%;">
	<tr><td colspan=8><?php $_POST['adddk'] = isset($_POST['adddk']) ? $_POST['adddk'] : 0;
		/*echo paramCheker("addf",$_POST['addf'],"Додати нового викладача",
								"onchange=\"submit()\""); */ ?></td></tr>
<?php
?>
	<tr><th rowspan=2>Код</th><th rowspan=2>Підрозділ</th>
		<th rowspan=2>Прізвище, ім'я та по батькові або повна назва</th>
		<th rowspan=2>Логін</th><th rowspan=2>Пароль</th>
                <th rowspan=2>Роль</th>
		<th colspan=2>Дії з об'єктом</th></tr>
	<tr><th>Змінити П.І.Б.</th><th>До видалення</th></tr>
<?php
// Завантажити перелік
	$DekansQuery = "SELECT a.id, a.fakul_id, b.fakultet_shufr AS subdiv, 
								a.dekan_name, a.login, a.passwd, c.Description
						FROM catalogDekan a, catalogFakultet b, catalogRoles c
						WHERE a.fakul_id = b.id AND a.role = c.id AND
								(c.Description = 'директор інституту' OR 
									c.Description = 'деканат')								
						ORDER BY subdiv, Description";
	$AdminsQuery = "SELECT a.id, a.fakul_id AS subdiv, a.dekan_name,
								a.login, a.passwd, b.Description
						FROM catalogDekan a, catalogRoles b
						WHERE a.role = b.id AND NOT
								(b.Description = 'директор інституту' OR 
									b.Description = 'деканат')								
						ORDER BY subdiv, Description";

	$D_result = mysqli_query($conn, $DekansQuery) or 
			die("Помилка сервера при запиті<br>".$DekansQuery." : ".mysqli_error($conn));
	$A_result = mysqli_query($conn, $AdminsQuery) or 
			die("Помилка сервера при запиті<br>".$AdminsQuery." : ".mysqli_error($conn));
	$icnt = 0;
	while ($query_row = mysqli_fetch_array($D_result)) { 
		$_POST['cbxdk'.$query_row['id']] = isset($_POST['cbxdk'.$query_row['id']]) ? 
															$_POST['cbxdk'.$query_row['id']] : "";
		$_POST['deldk'.$query_row['id']] = isset($_POST['deldk'.$query_row['id']]) ?
															$_POST['deldk'.$query_row['id']] : "";
?>
	<tr>		<td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['subdiv']; ?></td>                        
		<td style="text-align: left;">
			<?php $nbsps = ($query_row['Description'] == "директор інституту")
										? " &nbsp; &nbsp; " : "";
					echo $nbsps.$query_row['dekan_name']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['login']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['passwd']; ?></td>                        		
		<td style="text-align: left;"><?php echo $query_row['Description']; ?></td>
		<td><input type="checkbox" id="сbxdk<?php echo $query_row['id']; ?>" 
						name="cbxdk<?php echo $query_row['id']; ?>" class="del" />
<!--
			<label for="сbx<?php echo $query_row['id']; ?>" class="del">Змінити</label>
			<div> Назва: 
				<input type="textbox" name="tbxf<?php echo $query_row['id']; ?>" 
						ondblclick="submit()" style="font-weight: bold;"
						value="<?php echo $query_row['fakultet_name']; ?>" /><br>
				Шифр: 	
				<input type="textbox" name="tbxfc<?php echo $query_row['id']; ?>" 
						style="font-weight: bold;"
						value="<?php echo $query_row['fakultet_shufr']; ?>" /><br>
				<input type="submit" name="sbt" value="Зберегти" />			
			</div>
-->
		</td>
		<td><?php echo paramCheker("deldk".$query_row['id'],$_POST['deldk'.$query_row['id']],"",""); ?></td>
	</tr>
<?php 
		$icnt++;
	}
	while ($query_row = mysqli_fetch_array($A_result)) { 
		$_POST['cbxdk'.$query_row['id']] = isset($_POST['cbxdk'.$query_row['id']]) ? 
															$_POST['cbxdk'.$query_row['id']] : "";
		$_POST['deldk'.$query_row['id']] = isset($_POST['deldk'.$query_row['id']]) ?
															$_POST['deldk'.$query_row['id']] : "";
?>
	<tr>		<td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['subdiv']; ?></td>                        
		<td style="text-align: left;"><?php echo "*** ".$query_row['dekan_name']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['login']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['passwd']; ?></td>                        		
		<td style="text-align: left;"><?php echo $query_row['Description']; ?></td>
		<td><input type="checkbox" id="сbxdk<?php echo $query_row['id']; ?>" 
						name="cbxdk<?php echo $query_row['id']; ?>" class="del" />
<!--
			<label for="сbx<?php echo $query_row['id']; ?>" class="del">Змінити</label>
			<div> Назва: 
				<input type="textbox" name="tbxf<?php echo $query_row['id']; ?>" 
						ondblclick="submit()" style="font-weight: bold;"
						value="<?php echo $query_row['fakultet_name']; ?>" /><br>
				Шифр: 	
				<input type="textbox" name="tbxfc<?php echo $query_row['id']; ?>" 
						style="font-weight: bold;"
						value="<?php echo $query_row['fakultet_shufr']; ?>" /><br>
				<input type="submit" name="sbt" value="Зберегти" />			
			</div>
-->
		</td>
		<td><?php echo paramCheker("deldk".$query_row['id'],$_POST['deldk'.$query_row['id']],"",""); ?></td>
	</tr>
<?php 
		$icnt++;
	}
	if ($TrueAdmin) { ?>
		<tr><td colspan=7 style="text-align: right;">Усього: <?php echo bold($icnt); ?></td>
		<td><input type="checkbox" id="deldk" name="deldk" 
								onclick="if (confirm('Дійсно видалити позначених адміністраторів?')) submit();" class="del" />
						<label for="deldk" class="del">Видалити</label></td></tr>
<?php
	} ?>
</table>

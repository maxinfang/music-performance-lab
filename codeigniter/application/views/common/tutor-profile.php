<?php if ($record->getField('qualifications')) { ?>
<div class="field-row">
	<div class="field-label">Qualifications</div>
	<div class="field-field"><?php echo $record->getField('qualifications'); ?></div>
</div>
<?php } ?>

<?php if ($record->getField('instruments')) { ?>
<div class="field-row">
	<div class="field-label">Instruments/voice</div>
	<div class="field-field">
		<?php
			$instruments = $record->getField('instruments');
			$instruments = str_replace("\n", ', ', $instruments);
			$instruments = str_replace('Other', 'Other (' . $record->getField('instruments_other') . ')', $instruments);
			echo $instruments;
		?>
	</div>
</div>
<?php } ?>

<?php if ($record->getField('musical_styles')) { ?>
<div class="field-row">
	<div class="field-label">Musical styles</div>
	<div class="field-field"><?php echo $record->getField('musical_styles'); ?></div>
</div>
<?php } ?>

<?php if ($record->getField('biography')) { ?>
<div class="field-row">
	<div class="field-label">Biography</div>
	<div class="field-field"><?php echo nl2br($record->getField('biography')); ?></div>
</div>
<?php } ?>

<h3>Contact</h3>

<?php if ($record->getField('email')) { ?>
<div class="field-row">
	<div class="field-label">E-mail address</div>
	<div class="field-field"><?php echo mailto($record->getField('email')); ?></div>
</div>
<?php } ?>

<?php if ($record->getField('phone_mobile')) { ?>
<div class="field-row">
	<div class="field-label">Mobile contact</div>
	<div class="field-field"><?php echo $record->getField('phone_mobile'); ?></div>
</div>
<?php } ?>


<?php
	$address = array();
	if ($record->getField('address_line_1')) $address[] = $record->getField('address_line_1');
	if ($record->getField('address_line_2')) $address[] = $record->getField('address_line_2');
	if ($record->getField('suburb')) $address[] = strtoupper($record->getField('suburb'));
	if ($record->getField('postcode')) $address[] = $record->getField('postcode');
	if ($address) {
?>
<div class="field-row">
	<div class="field-label">Studio address</div>
	<div class="field-field"><?php echo implode ('<br />', $address); ?></div>
</div>
<?php } ?>

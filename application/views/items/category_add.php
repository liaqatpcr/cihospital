<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php 
// 'giftcards/save/'.$giftcard_id,
echo form_open('items/save_cat/1', array('id'=>'category_form', 'class'=>'form-horizontal')); ?>
	<fieldset id="giftcard_basic_info">
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('giftcards_person_id'), 'category_name', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'category_name',
						'id'=>'category_name',
						'class'=>'form-control input-sm',
						'value'=>'')
						);?>
			</div>
		</div>


		
	</fieldset>
<?php echo form_close(); ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	$("input[name='category_name']").change(function() {
		!$(this).val() && $(this).val('');
	});
	
	var fill_value = function(event, ui) {
		event.preventDefault();
		$("input[name='category_name']").val(ui.item.label);
	};

	/*var autocompleter = $('#category_name').autocomplete({
		source: '<?php echo site_url("customers/suggest"); ?>',
		minChars: 0,
		delay: 15, 
	   	cacheLength: 1,
		appendTo: '.modal-content',
		select: fill_value,
		focus: fill_value
	});*/

	// declare submitHandler as an object.. will be reused
	var submit_form = function() { 
		$(this).ajaxSubmit({
			success: function(response)
			{
				dialog_support.hide();
				table_support.handle_submit('<?php echo site_url($controller_name); ?>', response);
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				table_support.handle_submit('<?php echo site_url($controller_name); ?>', {message: errorThrown});
			},
			dataType: 'json'
		});
	};
	
	$('#category_form').validate($.extend(form_support.handler,
	{
		submitHandler:function(form)
		{
			submit_form.call(form)
		},
		rules:
		{
			<?php
			if($this->config->item('giftcard_number') == "series")
			{
			?>
			giftcard_number:
 			{
 				required: true,
 				number: true
 			},
 			<?php
			}
			?>
			giftcard_amount:
			{
				required: true,
				remote:
				{
					url: "<?php echo site_url($controller_name . '/ajax_check_number_giftcard')?>",
					type: 'POST',
					data: {
						'amount': $('#giftcard_amount').val()
					},
					dataFilter: function(data) {
						setup_csrf_token();
						var response = JSON.parse(data);
						$('#giftcard_amount').text(response.value);
						return response.success;
					}
				}
			}
		},
		messages:
		{
			<?php
			if($this->config->item('giftcard_number') == "series")
			{
			?>
			giftcard_number:
 			{
 				required: "<?php echo $this->lang->line('giftcards_number_required'); ?>",
 				number: "<?php echo $this->lang->line('giftcards_number'); ?>"
 			},
 			<?php
			}
			?>
			giftcard_amount:
			{
				required: "<?php echo $this->lang->line('giftcards_value_required'); ?>",
				remote: "<?php echo $this->lang->line('giftcards_value'); ?>"
			}
		}
	}, form_support.error));
});
</script>

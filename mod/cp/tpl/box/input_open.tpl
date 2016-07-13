<!--set $_input_id ++-->
<!--set $t_class = '' -->
<!--if $current_delimiter.isset() -->
<!--set $t_class = ' fk_delimiter_' . $current_delimiter -->
<!--/if-->
<tr class="form_line{$t_class}">
<td width="7%" align="center" class="form_name form_line_td" valign="top"><!--if $item.pk_prop.isset() --><span class="system">{$prop_counter}</span><!--/if--></td>
<td width="7%" class="form_name form_line_td <!--if $form.object->error.$item&name.isset()-->form_flag_bad<!--/if-->" valign="top">
<!--if $item.description !=''-->
<label class="system" title="{$item.description}">{$item.call}</label>
<!--else-->
<span class="system">{$item.call}</span>
<!--/if-->
<!--if $item.necessary-->
<span>*</span>
<!--/if-->
</td>
<td width="7%"></td>
<td class="form_line_td">
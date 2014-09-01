{*

    default tpl for a filtertype form view

*}
{def $filter_identifier = $filtertype_object.identifier
     $filter_name = $filtertype_object.name
     $values_available = $filtertype_object.values_available
     $values_selected = $filtertype_object.values
     $operations_available = $filtertype_object.operations_available
     $operation_selected = $filtertype_object.operation
      }

<tr class="filter" id="tr_filter_{$filter_index}">
    <td style="width: 50px;">
        {*<input type="checkbox" name="F[{$filter_index}][a]" value="1" id="active_{$filter_index}">*}

        <input type="checkbox" name="{$post_var_remove}" value="{$filter_index}" />

    </td>
    <td style="width: 200px;">
        <input type="hidden" name="F[{$filter_index}][i]" value="{$filter_identifier}" id="name_{$filter_index}">
        <label for="cb_status_id">{$filter_name}</label>
    </td>
    <td style="width: 150px;">
        <select style="vertical-align: top;" class="select-small" name="F[{$filter_index}][o]" id="operation_{$filter_index}">
        {foreach $operations_available as $key => $name}
            <option value="{$key}"{if $key|eq( $operation_selected )} selected="selected"{/if}>{$name}</option>
        {/foreach}
        </select>
    </td>
    <td>

        {if is_array( $values_available )}
            <select name="F[{$filter_index}][v][]" id="values_{$filter_index}"{if $values_selected|count|gt( 1 )}multiple="multiple"{/if}>
            {foreach $values_available as $key => $name}
                <option value="{$key}"{if $values_selected|contains( $key )} selected="selected"{/if}>{$name}</option>
            {/foreach}
            </select>
            <a style="vertical-align: bottom;" onclick="toggleMultiSelect( '{$filter_index}' ); return false;" href="#">[+]</a>
        {else}

            {if $values_selected|count|eq( 0 )}
                {set $values_selected = array( '' )}
            {/if}

            {foreach $values_selected as $value}
                <input type="text" name="F[{$filter_index}][v][]" id="values_{$filter_index}" value="{$value}">
            {/foreach}
        {/if}
    </td>
</tr>

{undef $identifier}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Newsletter Edition send out statistic'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="mainobject-window">

<div class="block">

    <div class="context-attributes">

<p>{"Current Date"|i18n("cjw_newsletter/cjw_newsletter_edition_send_statistic")}: {currentdate()|l10n( 'datetime' )}</p>


        <table class="list" cellspacing="0">
        <tr>
            <th>{'Id'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>
            <th>{'Mail count'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>
            <th>{'Mail send'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>
            <th>{'Mail not send'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>
            <th>{'Mail bounced'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>
            <th>{'Creator'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>
            <th></th>

        </tr>

        {*$edition_attribute_content.edition_send_array.all|attribute(show)*}
        {if $newsletter_edition_attribute_content.edition_send_array.current|count|ne( 0 )}
            {def $current_edition_send_object = $newsletter_edition_attribute_content.edition_send_array.current[0]}
        {/if}

        {foreach $newsletter_edition_attribute_content.edition_send_array.all as $edition_send_object}
         <tr>
            <td>{if $current_edition_send_object.id|eq($edition_send_object.id)}({$edition_send_object.id}){else}{$edition_send_object.id}{/if}</td>
            <td>{$edition_send_object.send_items_statistic.items_count}</td>
            <td>{$edition_send_object.send_items_statistic.items_send}</td>
            <td>{$edition_send_object.send_items_statistic.items_not_send}</td>
            <td>{$edition_send_object.send_items_statistic.items_bounced}</td>
            <td>{$edition_send_object.creator_id}</td>
            <td>
            {* only show abort button if not  finished (3) or aborted (9) *}
            {if or( $edition_send_object.status|eq(3), $edition_send_object.status|eq(9) )}
                {'Abort cronjob'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}
            {else}
                <a href={concat('newsletter/send_abort/',$edition_send_object.id)|ezurl}>
                {'Abort cronjob'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}
                </a>
            {/if}</td>
        </tr>
        <tr>

        <td colspan="7">
        Cronjob Status: {*{$edition_send_object.status}*}
            <ul>
            <li>{if $edition_send_object.status|eq(0)}<b>{/if}0 - wait_for_process ( {$edition_send_object.created|l10n( shortdatetime )} ){if $edition_send_object.status|eq(0)}</b>{/if}</li>
            <li>{if $edition_send_object.status|eq(1)}<b>{/if}1 - mailqueue_created ( {cond( $edition_send_object.mailqueue_created|eq(0), '-',  $edition_send_object.mailqueue_created|l10n( shortdatetime ) )} ){if $edition_send_object.status|eq(1)}</b>{/if}</li>
            <li>{if $edition_send_object.status|eq(2)}<b>{/if}2 - mailqueue_process_started ( {cond( $edition_send_object.mailqueue_process_started|eq(0), '-',  $edition_send_object.mailqueue_process_started|l10n( shortdatetime ) )} ){if $edition_send_object.status|eq(2)}</b>{/if}</li>
            <li>{if $edition_send_object.status|eq(3)}<b>{/if}3 - mailqueue_process_finished ( {cond( $edition_send_object.mailqueue_process_finished|eq(0), '-',  $edition_send_object.mailqueue_process_finished|l10n( shortdatetime ) )} ){if $edition_send_object.status|eq(3)}</b>{/if}</li>
            <li>{if $edition_send_object.status|eq(9)}<b>{/if}9 - mailqueue_process_aborted ( {cond( $edition_send_object.mailqueue_process_aborted|eq(0), '-',  $edition_send_object.mailqueue_process_aborted|l10n( shortdatetime ) )} ){if $edition_send_object.status|eq(9)}</b>{/if}</li>
            </ul>

        </td>
        </tr>
        {/foreach}
        {*$editionSendObject|attribute(show)*}
        </table>

</div>

</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

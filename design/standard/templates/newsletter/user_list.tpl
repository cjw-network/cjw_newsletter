{*  newsletter/user_list.tpl
nur nodeobject vom type  cjw_newsletter_list aktzeptieren
*}
<div class="newsletter newsletter-user_list">
{def $limit = 50}
{if ezpreference( 'admin_user_list_limit' )}
    {switch match=ezpreference( 'admin_user_list_limit' )}
        {case match=1}
        {set $limit=10}
        {/case}
        {case match=2}
        {set $limit=25}
        {/case}
        {case match=3}
        {set $limit=50}
        {/case}
    {/switch}
{/if}
    {*

    {def $user_list = fetch('newsletter', 'user_list',
                            hash( 'offset', $view_parameters.offset,
                                  'limit', $limit,
                                  'email_search', $view_parameters.search_user_email,
                                  'sort_by', hash('created', 'desc' ) ))
                                  }

    {def $user_list_count = fetch('newsletter', 'user_list_count',
                               hash('email_search', $view_parameters.search_user_email ))
     $page_uri = 'newsletter/user_list'}

    *}

    {def $page_uri = 'newsletter/user_list'}

    <div class="context-block">
    {* DESIGN: Header START *}
    <div class="box-header">
        <div class="box-tc">
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-tl">
                        <div class="box-tr">
                            <h1 class="context-title">{'Manage users'|i18n( 'cjw_newsletter/user_list',, hash() )|wash}</h1>
                            {* DESIGN: Mainline *}
                            <div class="header-mainline">
                            </div>
                            {* DESIGN: Header END *}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {* DESIGN: Content START *}
    <div class="box-ml">
        <div class="box-mr">
            <div class="box-content">
                <div class="context-attributes">
                    <div class="block float-break">
                        <form action={$page_uri|ezurl} name="UserList" method="post">

                            {*Alles: <input type="text" name="SearchText" value="">
                            <br/>
                            *}
                            {'Email'|i18n( 'cjw_newsletter/user_list' )}: <input type="text" name="SearchUserEmail" value="{if is_set($view_parameters['search_user_email'])}{$view_parameters['search_user_email']}{/if}"><input type="submit" name="SubmitUserSearch" value="{'Search for existing user'|i18n( 'cjw_newsletter/user_list' )}">
                        </form>{* Created. *}
                    </div>
                </div>
                {* DESIGN: Content END *}
            </div>
        </div>
    </div>
    {* Buttons. *}
    <div class="controlbar">
        {* DESIGN: Control bar START *}
        <div class="box-bc">
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-tc">
                        <div class="box-bl">
                            <div class="box-br">
                                {* Edit *}
                                <div class="left">

                                     <form method="post" style="display:inline;" action={'newsletter/user_create/-1'|ezurl}>
                                            <input class="button" type="submit" name="CreateNewsletterUserButton" value="{'Create Newsletter user'|i18n( 'cjw_newsletter/user_list' )}" />
                                     </form>
                                    {*
                                    <form name="edit_user_list" method="post" action={concat( '/newsletter/edit_user_list/', $newsletter_list_node.url_alias )|ezurl}  style="display:inline">
                                        <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/rss/edit_import' )}" title="{'Edit current subscription list.'|i18n( 'cjw_newsletter/user_list' )}" />
                                    </form>*}
                                    {*
                                    <form name="import_csv" method="post" action={$uri_csv_import|ezurl} style="display:inline">
                                        <input class="button" type="submit" name="importcsv" value="{'Import CSV'|i18n( 'cjw_newsletter/user_list' )}" title="{'Import contact from CSV file.'|i18n( 'cjw_newsletter/newsletter_list_subscription' )}" />
                                    </form>*}
                                </div>
                            </div>{* DESIGN: Control bar END *}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h2 class="context-title">{'Users'|i18n( 'cjw_newsletter/user_list',, hash( ) )} [{$user_list_count}]</h2>
                                {* DESIGN: Subline *}
                                <div class="header-subline">
                                </div>
                                {* DESIGN: Header END *}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {* DESIGN:  START *}
        <div class="box-ml">
            <div class="box-mr">
                <div class="box-content">

                    <div class="context-toolbar">
                        <div class="button-left">
                            <p class="table-preferences">
                                {switch match=$limit}
                                {case match=25}<a href={'/user/preferences/set/admin_user_list_limit/1'|ezurl}  title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10 </a>
                                <span class="current">25</span>
                                <a href={'/user/preferences/set/admin_user_list_limit/3'|ezurl}  title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50 </a>
                                {/case}
                                {case match=50}<a href={'/user/preferences/set/admin_user_list_limit/1'|ezurl}  title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10 </a>
                                <a href={'/user/preferences/set/admin_user_list_limit/2'|ezurl}  title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25 </a>
                                <span class="current">50</span>
                                {/case}
                                {case}<span class="current">10</span>
                                <a href={'/user/preferences/set/admin_user_list_limit/2'|ezurl}  title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25 </a>
                                <a href={'/user/preferences/set/admin_user_list_limit/3'|ezurl}  title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50 </a>
                                {/case}
                                {/switch}
                            </p>
                        </div>
                    </div>
                    <div class="break float-break">
                    </div>

                    <div class="content-navigation-childlist overflow-table">

                    <table class="list" cellspacing="0">
                        <tr>
                        {*    <th class="tight">
                                <img src={'toggle-button-16x16.gif'|ezimage}  alt="{'Invert selection'|i18n( 'cjw_newsletter/user_list' )}" title="{'Invert selection'|i18n( 'cjw_newsletter/user_list' )}" onclick="ezjs_toggleCheckboxes( document.user_list, 'SubscriptionIDArray[]' ); return false;" />
                            </th>*}
                        <th class="tight">
                            {'UID'|i18n('cjw_newsletter/user_list')}</a>
                        </th>
                        <th>
                            {'Email'|i18n( 'cjw_newsletter/user_list' )}
                        </th>
                  {*      <th>
                            {'Name'|i18n( 'cjw_newsletter/user_list' )}
                        </th> *}
                        <th>
                            {'Lists'|i18n( 'cjw_newsletter/user_list' )}
                        </th>
                        <th title="{'Confirmed'|i18n( 'cjw_newsletter/user_list' )}">
                            {'Conf'|i18n( 'cjw_newsletter/user_list' )}
                        </th>
                        <th title="{'Blacklisted'|i18n( 'cjw_newsletter/user_list' )}">
                            {'Black'|i18n( 'cjw_newsletter/user_list' )}
                        </th>
                        <th>
                            {'Bounce'|i18n( 'cjw_newsletter/user_list' )}
                        </th>
                        <th>
                            {'Status'|i18n( 'cjw_newsletter/user_list' )}
                        </th>
                        <th>
                            {'eZ user id'|i18n( 'cjw_newsletter/user_list' )}
                        </th>
                        <th class="edit">
                            {* user_edit *}
                        </th>
                        </tr>
                        {foreach $user_list as $newsletter_user sequence array( bglight, bgdark ) as $style}
                        <tr class="{$style}">
                          {*  <td>
                                <input type="checkbox" name="SubscriptionIDArray[]" value="{$newsletter_user.id}" title="{'Select subscriber for removal'|i18n( 'cjw_newsletter/user_list' )}" />
                            </td> *}
                            <td class="number" align="right">
                                <a href={concat('newsletter/user_view/',$newsletter_user.id)|ezurl} title="{'Newsletter user id'|i18n( 'cjw_newsletter/user_list' )}">{$newsletter_user.id} </a>
                            </td>
                            <td>
                                <a href={concat('newsletter/user_view/',$newsletter_user.id)|ezurl} title="{$newsletter_user.first_name} {$newsletter_user.last_name}">{$newsletter_user.email|wash}</a>
                            </td>
                 {*           <td>
                                {$newsletter_user.name|wash}
                            </td>*}
                            <td title="{'Approved'|i18n( 'cjw_newsletter/user_list' )} / {'All'|i18n( 'cjw_newsletter/user_list' )}">
                                {def $approved_subscribtion_count = 0
                                     $subscription_array = $newsletter_user.subscription_array}
                                {foreach $subscription_array as $subscription}
                                    {*if approved*}
                                    {if $subscription.status|eq( 2 )}
                                        {set $approved_subscribtion_count = $approved_subscribtion_count|inc}
                                    {/if}

                                {/foreach}
                                <b>{$approved_subscribtion_count}</b> / {$subscription_array|count}
                                {undef $approved_subscribtion_count $subscription_array}
                            </td>
                            <td {cond($newsletter_user.confirmed|gt(0), concat('title="',$newsletter_user.confirmed|l10n(  shortdatetime ),'"') ,  '' )}>
                                {cond($newsletter_user.confirmed|gt(0), 'x', '-')}{*$newsletter_user.confirmed|l10n(shortdatetime)*}
                            </td>
                            <td>
                                {cond($newsletter_user.blacklisted|gt(0),'x' , '-' )}
                            </td>
                            <td title="{'Bounced'|i18n( 'cjw_newsletter/user_list' )} / {'Bounce count'|i18n( 'cjw_newsletter/user_list' )}">
                                {cond($newsletter_user.bounced|gt(0),'x' , '-' )} / {$newsletter_user.bounce_count|wash}
                            </td>
                            <td title="{$newsletter_user.status|wash}">
                                {$newsletter_user.status_string|wash}
                            </td>
                            <td>
                                 {cond($newsletter_user.ez_user_id|gt(0), $newsletter_user.ez_user_id , '-' )}
                            </td>
                            <td>
                                <a href={concat( 'newsletter/user_edit/', $newsletter_user.id, '?RedirectUrl=newsletter/user_list/(offset)/', $view_parameters.offset )|ezurl}>
                                    <img title="{'Edit newsletter user'|i18n( 'cjw_newsletter/user_list' )}" alt="{'Edit newsletter user'|i18n( 'cjw_newsletter/user_list' )}" src={'edit.gif'|ezimage()} />
                                </a>
                            </td>

                        </tr>{/foreach}
                    </table>
                </div>

                {* Navigator. *}
                <div class="context-toolbar subitems-context-toolbar">
                    {include name='Navigator'
                             uri='design:navigator/google.tpl'
                             page_uri=$page_uri
                             item_count=$user_list_count
                             view_parameters=$view_parameters
                             item_limit=$limit}
                </div>

                    {* DESIGN: Table END *}
                </div>
            </div>
            <div class="controlbar">
                {* DESIGN: Control bar START *}
                <div class="box-bc">
                    <div class="box-ml">
                        <div class="box-mr">
                            <div class="box-tc">
                                <div class="box-bl">
                                    <div class="box-br">

                                    </div>{* DESIGN: Control bar END *}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>{* DESIGN: Content END *}
    </div>
</div>

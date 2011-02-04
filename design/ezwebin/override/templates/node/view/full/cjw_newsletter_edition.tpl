{set-block scope=root variable=cache_ttl}0{/set-block}


{include uri='design:infocollection_validation.tpl'}
<div class="newsletter content-view-full">
    <div class="class-{$node.class_identifier}">

    <div class="border-box">
    <div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
    <div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

    <div class="content-navigation">
        {* Content window. *}
        <div class="context-block">
            {* DESIGN: Header START *}
            <div class="box-header">
                <div class="box-tc">
                    <div class="box-ml">
                        <div class="box-mr">
                            <div class="box-tl">
                                <div class="box-tr">
                                    {def $hide_status=""}
                                    {if $node.is_invisible}
                                        {set hide_status=concat( '(', $node.hidden_status_string, ')' )}
                                    {/if}
                                    <h1 class="context-title">{$node.name|wash}&nbsp;[{$node.class_name|wash}]&nbsp;{$hide_status}</h1>
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
        </div>
        {include uri="design:cjw_newsletter_edition_windows.tpl"}
    </div>


    </div></div></div>
    <div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
    </div>

    </div>
</div>
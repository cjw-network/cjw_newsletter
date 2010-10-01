{* Window controls. *}
<div class="menu-block{section show=fetch( content, translation_list )|count|eq( 1 )} notranslations{/section}">
    {def $translations_count=count($node.object.available_languages)
        $li_width="" }
    {def $can_create_languages=fetch( content, prioritized_languages )}
    {if le($can_create_languages, 1) }
        {set $li_width="_25"}
    {/if}

    <ul>
        {* Content preview. *}
        {section show=ezpreference( 'admin_navigation_content' )}
            <li class="enabled {$li_width}">
                <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
                    <a href={'/user/preferences/set/admin_navigation_content/0'|ezurl} title="{'Hide preview of content.'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}">{'Preview'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}</a>
                </div></div></div></div>
            </li>
        {section-else}
            <li class="disabled {$li_width}">
                <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
                    <a href={'/user/preferences/set/admin_navigation_content/1'|ezurl} title="{'Show preview of content.'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}">{'Preview'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}</a>
                </div></div></div></div>
            </li>
        {/section}

        {* Details. *}
        {section show=ezpreference( 'admin_navigation_details' )}
            <li class="enabled {$li_width}">
                <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
                    <a href={'/user/preferences/set/admin_navigation_details/0'|ezurl} title="{'Hide details.'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}">{'Details'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}</a>
                </div></div></div></div>
            </li>
        {section-else}
            <li class="disabled {$li_width}">
                <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
                    <a href={'/user/preferences/set/admin_navigation_details/1'|ezurl} title="{'Show details.'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}">{'Details'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}</a>
                </div></div></div></div>
            </li>
        {/section}

        {* Translations. *}
        {section show=fetch( content, translation_list )|count|gt( 1 )}

            {if gt($can_create_languages, 1) }
                {section show=ezpreference( 'admin_navigation_translations' )}
                    <li class="enabled {$li_width}">
                        <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
                            <a href={'/user/preferences/set/admin_navigation_translations/0'|ezurl} title="{'Hide available translations.'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}">{'Translations'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}</a>
                        </div></div></div></div>
                    </li>
                {section-else}
                    <li class="disabled {$li_width}">
                        <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
                            <a href={'/user/preferences/set/admin_navigation_translations/1'|ezurl} title="{'Show available translations.'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}">{'Translations'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}</a>
                        </div></div></div></div>
                    </li>
                {/section}
            {/if}
        {/section}

        {* Locations. *}
        {section show=ezpreference( 'admin_navigation_locations' )}
            <li class="enabled {$li_width}">
                <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
                    <a href={'/user/preferences/set/admin_navigation_locations/0'|ezurl} title="{'Hide location overview.'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}">{'Locations'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}</a>
                </div></div></div></div>
            </li>
        {section-else}
            <li class="disabled {$li_width}">
                <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
                    <a href={'/user/preferences/set/admin_navigation_locations/1'|ezurl} title="{'Show location overview.'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}">{'Locations'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}</a>
                </div></div></div></div>
            </li>
        {/section}

        {* Relations. *}
        {section show=ezpreference( 'admin_navigation_relations' )}
            <li class="enabled {$li_width}">
                <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
                    <a href={'/user/preferences/set/admin_navigation_relations/0'|ezurl} title="{'Hide object relation overview.'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}">{'Relations'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}</a>
                </div></div></div></div>
            </li>
        {section-else}
            <li class="disabled {$li_width}">
                <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
                    <a href={'/user/preferences/set/admin_navigation_relations/1'|ezurl} title="{'Show object relation overview.'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}">{'Relations'|i18n( 'cjw_newsletter/cjw_newsletter_list_window_controls' )}</a>
                </div></div></div></div>
            </li>
        {/section}
    </ul>

    <div class="break"></div>

    {undef $li_width $translations_count}

</div>

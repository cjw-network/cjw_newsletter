

    {*<p><label>{'State'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )}Status:</label>*}
        {switch match=$newsletter_edition_status}
            {case match='draft'}
                <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_draft.png'|ezdesign} alt="{$newsletter_edition_status}" title="{$newsletter_edition_status}" />
                {'Status draft'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )}
                {*'Send Newsletter'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )*}{*Newsletter verschicken*}
            {/case}
            {case match='process'}
                <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_process.png'|ezdesign} alt="{$newsletter_edition_status}" title="{$newsletter_edition_status}" />
                {'Status process'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )}
                {*'in the dispatch'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )*}    {*im Versand*}
            {/case}
            {case match='archive'}
                <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_archive.png'|ezdesign} alt="{$newsletter_edition_status}" title="{$newsletter_edition_status}" />
                {'Status archive'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )}
                {*'sends'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )*}      {*verschickt*}
            {/case}
            {case match='abort'}
                <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_abort.png'|ezdesign} alt="{$newsletter_edition_status}" title="{$newsletter_edition_status}" />
                {*'uncompletedly'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )*}      {*abgebrochen*}
                {'Status abort'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )}
            {/case}
        {/switch}


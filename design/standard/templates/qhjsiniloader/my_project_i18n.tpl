{**
 * This output a JSON formated list of translatable texts
 * WARNING:
 *   Either add a |wash() after the |i18n call or make sure your translation file
 *   is safe for a JSON context (watchout for double quotes and ampersand for example).
 *}
{ldelim}
"test1":"{'Test text 1'|i18n('design/standard/qhjsiniloader')}",
"test2":"{'Test text 2 to translate'|i18n('design/standard/qhjsiniloader')}"
{rdelim}

/* 
 *  This is a generic file which is needed for plugins that are developed
 *  for FCKEditor. With the below statements that toolbar is created and
 *  several options are being activated.
 *
 *  See the online documentation for more information:
 *  http://wiki.fckeditor.net/
 *
 *  Ninth Avenue Software 2012
 *  http://www.ninthavenue.com.au
 */

FCKLang.EmbedBtn = 'Embed Multimedia';
FCKLang.EmbedDlgTitle = 'Embed Multimedia';
FCKLang.EmbedDlgName = 'Embed Multimedia';
FCKLang.EmbedTooltip = 'Embed multimedia content from YouTube, Vimeo or any other service.';

// Register the related commands.
FCKCommands.RegisterCommand('Embed',
  new FCKDialogCommand('Embed', FCKLang.EmbedDlgTitle, FCKPlugins.Items.embed.Path + 'fck_embed.html',
    450, 370
  )
);
 
// Create the "Embed" toolbar button.
// FCKToolbarButton( commandName, label, tooltip, style, sourceView, contextSensitive )
var oEmbedItem = new FCKToolbarButton( 'Embed', FCKLang.EmbedBtn, FCKLang.EmbedTooltip, null, false, false); 
oEmbedItem.IconPath = FCKPlugins.Items.embed.Path + '/fck_embed.gif'; 
FCKToolbarItems.RegisterItem( 'Embed', oEmbedItem );

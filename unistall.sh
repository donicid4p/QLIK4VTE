#!/bin/bash

# Chiedi all'utente di inserire la root directory
echo "Root directory:"
read -r input_string

root_dir="$input_string"

echo "Root directory: $root_dir"


 #se il file esiste
if [ -f "$root_dir/themes/images/QlikIframe.php" ]; then
        #rimuovilo
        rm "$root_dir/themes/images/QlikIframe.php" 
        echo "$root_dir/themes/images/QlikIframe.php eliminated!\n"
fi
if [ -f "$root_dir/themes/images/logo_qlikvte.png" ]; then
        #rimuovilo
        rm "$root_dir/themes/images/logo_qlikvte.png"
        echo "$root_dir/themes/images/logo_qlikvte.png eliminated!\n"
fi
if [ -f "$root_dir/themes/images/vteQlik20x20.png" ]; then
        #rimuovilo
        rm "$root_dir/themes/images/vteQlik20x20.png"
        echo "$root_dir/themes/images/vteQlik20x20.png eliminated!\n"
fi
if [ -f "$root_dir/themes/images/vteQlik50x50_Color.png" ]; then
        #rimuovilo
        rm "$root_dir/themes/images/vteQlik50x50_Color.png"
        echo "$root_dir/themes/images/vteQlik50x50_Color.png eliminated!\n"
fi
if [ -f "$root_dir/themes/images/vteQlik50x50.png" ]; then
        #rimuovilo
        rm "$root_dir/themes/images/vteQlik50x50.png"
        echo "$root_dir/themes/images/vteQlik50x50.png eliminated!\n"
fi
# Ripristina il file originale style.css se esiste
if [ -f "$root_dir/themes/next22/style.css.old" ]; then
    rm "$root_dir/themes/next22/style.css"
    mv "$root_dir/themes/next22/style.css.old" "$root_dir/themes/next22/style.css"
    echo "$root_dir/themes/next22/style.css Restored"
fi


# Ripristina il file originale theme.php se esiste
if [ -f "$root_dir/themes/next22/theme.php.old" ]; then
    rm "$root_dir/themes/next22/theme.php"
    mv "$root_dir/themes/next22/theme.php.old" "$root_dir/themes/next22/theme.php"
    echo "$root_dir/themes/next22/theme.php Restored"
fi




if [ -f "$root_dir/Smarty/templates/DetailViewUI.old.tpl" ]; then
        #rimetti l'originale
        rm "$root_dir/Smarty/templates/DetailViewUI.tpl"
        mv "$root_dir/Smarty/templates/DetailViewUI.old.tpl" "$root_dir/Smarty/templates/DetailViewUI.tpl"
        echo "$root_dir/Smarty/templates/DetailViewUI.tpl Restored"
fi

if [ -f "$root_dir/Smarty/templates/EditViewUI.old.tpl" ]; then
        #rimetti l'originale
        rm "$root_dir/Smarty/templates/EditViewUI.tpl"
        mv "$root_dir/Smarty/templates/EditViewUI.old.tpl" "$root_dir/Smarty/templates/EditViewUI.tpl"
        echo "$root_dir/Smarty/templates/EditViewUI.tpl Restored"
fi


# Rimuovi la cartella QlikProxy da root_dir/
if [ -d "$root_dir/QlikProxy" ]; then
        rm -r "$root_dir/QlikProxy"
fi


if [ -d "$root_dir/modules/QlikIframe" ]; then
        rm -r "$root_dir/modules/QlikIframe"
fi

if [ -d "$root_dir/modules/QlikIframe" ]; then
        rm -r "$root_dir/modules/QlikIframe"
fi

if [ -d "$root_dir/modules/Settings/QlikIframe" ]; then
        rm -r "$root_dir/modules/Settings/QlikIframe"
fi

if [ -d "$root_dir/Smarty/templates/QlikIframe" ]; then
        rm -r "$root_dir/Smarty/templates/QlikIframe"
fi

if [ -d "$root_dir/modules/SDK/qlik" ]; then
        rm -r "$root_dir/modules/SDK/qlik"
fi

if [ -f  "$root_dir/modules/Settings/QlikIframe.php" ]; then
        rm  "$root_dir/modules/Settings/QlikIframe.php"
fi

if [ -f  "$root_dir/modules/Settings/resources/QlikIframe.js" ]; then
        rm  "$root_dir/modules/Settings/resources/QlikIframe.js"
fi


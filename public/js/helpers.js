/**
 * Get the extension for a given file.
 * @param filename
 * @returns {*}
 */
function getFileExtension(filename)
{
    if (!filename)
    {
        return null;
    }

    var pos = filename.lastIndexOf('.');

    if (pos >= 0 && filename.substring(-1) != '.') {
        return 'ext_' + filename.substr(pos + 1);
    } else {
        return '';
    }
}

RegExp.escape = function(s) {
    return s.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
};

function basename(path) {

    if (path.charAt(path.length - 1) == '/') {
        path = path.substring(0, path.length - 1);
    }

    return path.replace(/^.*[\/\\]/g, '');
}

function dirname(path) {
    return path.substring(0, path.lastIndexOf(basename(path)));
}

/**
 * Given ul.jqueryFileTree element, sort the folder.
 * @param $folder
 */
function sortFolder($folder) {
    console.log('In sortFolder');
    var $contents = $folder.children('li').get();

    $contents.sort(function (a, b) {
        var $a = $(a), $b = $(b);

        if ($a.hasClass('directory') && $b.hasClass('file')) {
            return -1;
        } else if ($a.hasClass('file') && $b.hasClass('directory')) {
            return 1;
        } else {
            var aTxt = $a.children('a').first().text();
            var bTxt = $b.children('a').first().text();
            if (aTxt === bTxt) { return 0; }
            return aTxt < bTxt ? -1 : 1;
        }
    });

    $.each($contents, function(index, item) {
        $folder.append(item);
    });
}

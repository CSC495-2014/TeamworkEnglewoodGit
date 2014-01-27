$(function() {
    var directoryItems = {
        'newFolder': {name: 'New Folder...', icon: 'add-dir', fscallback: onClickFsDirNewDir},
        'newFile': {name: 'New File...', icon: 'add-file', fscallback: onClickFsDirNewFile},
        'rename': {name: 'Rename...', icon: 'edit', fscallback: onClickFsRename},
        'copy': {name: 'Copy', icon: 'copy', fscallback: onClickFsCopy},
        'paste': {name: 'Paste', icon: 'paste', fscallback: onClickFsPaste},
        'gitAdd': {name: 'Git Add', icon: 'git-add', fscallback: onClickFsItemGitAdd},
        'delete': {name: 'Delete...', icon: 'delete', fscallback: onClickFsDirDelete},
        'sep1': '------------',
        'quit': {name: 'Quit', icon: 'quit'}
    };

    var fileItems = {
        'rename': {name: 'Rename...', icon: 'edit', fscallback: onClickFsRename},
        'copy': {name: 'Copy', icon: 'copy', fscallback: onClickFsCopy},
        'gitAdd': {name: 'Git Add', icon: 'git-add', fscallback: onClickFsItemGitAdd},
        'delete': {name: 'Delete...', icon: 'delete', fscallback: onClickFsFileDelete},
        'sep1': '------------',
        'quit': {name: 'Quit', icon: 'quit'}
    };

    /* *****************
     * Global Variables
     *******************/

    /**
     * Holds path for cut/copy/paste commands.
     *
     * @type {string}
     * @private
     */
    var _clipboard = null;
    var $_confirmModal = $('#confirmModal');
    var $_promptModal = $('#promptModal');

    /* ********************************
     * Filesystem context menu events.
     **********************************/
    function onClickFsRename(name, path, options) { fsRenameItemPrompt(name, path, fsMv); }
    function onClickFsCopy(name, path, options) { _clipboard = path; console.log('Clipboard: ' + path); }
    function onClickFsPaste(name, path, options) { fsCp(_clipboard, path); }
    function onClickFsDirNewDir(name, path, options) { fsNewItemPrompt(path, 'Directory', 'ExampleDirectory', fsMkdir); }
    function onClickFsDirNewFile(name, path, options) { fsNewItemPrompt(path, 'File', 'ExampleFile.html', fsTouch); }
    function onClickFsDirDelete(name, path, options) { fsConfirmDelete(path, fsRmdir); }
    function onClickFsFileDelete(name, path, options) { fsConfirmDelete(path, fsRm); }
    function onClickFsItemGitAdd(name, path, options) { /* git add item */ }

    /**
     * Callback for all context menu events.
     *
     * @param {string} key      Key for context item pressed.
     * @param {Object} options  All information about the key press event.
     */
    function contextMenuCallback(key, options) {
        // Get the path from the clicked link.
        var $link = options.$trigger.find('a:first');
        var type = options.$trigger.hasClass('directory') ? 'directory' : 'file';
        var name = $link.text();
        var path = $link.attr('rel');
        var callback = options.items[key]['fscallback'];

        console.log('clicked ' + type + ' ' + path + ': ' + key);

        if (callback) {
            callback(name, path, options);
        }
    }

    // Directory context menu.
    $.contextMenu({
        selector: '.directory',
        callback: contextMenuCallback,
        items: directoryItems
    });

    // File context menu.
    $.contextMenu({
        selector: '.file',
        callback: contextMenuCallback,
        items: fileItems
    });

    /* ********************
     * Filesystem prompts.
     **********************/

    /**
     * Launch a modal to prompt the user for the name of a new item.
     *
     * @param {string}   oldName    Name of item.
     * @param {string}   oldPath    Path to item.
     * @param {function} callback   function(oldPath, newPath)
     */
    function fsRenameItemPrompt(oldName, oldPath, callback) {
        var $positiveButton = $_promptModal.find('button.positive');
        var isDir = oldPath.charAt(oldPath.length - 1) == '/';

        $_promptModal.find('#promptModalInput').val(oldName);
        $_promptModal.find('.modal-title').text('Rename ' + oldName);
        $positiveButton.text('Rename');
        $positiveButton.on('click', function() {
            var newName = $_promptModal.find('#promptModalInput').val();

            var parent = isDir ? oldPath.substr(0, oldPath.length - 1) : oldPath;
            parent = oldPath.substr(0, parent.lastIndexOf('/') + 1);

            var newPath = parent + newName + (isDir ? '/' : '');
            console.log(newPath);

            $_promptModal.modal('hide');
            callback(oldPath, newPath);
        });

        $_promptModal.modal();
    }

    /**
     * Launch a modal to prompt the user for the name of a new item.
     *
     * @param {string}   parent         Path of item parent directory.
     * @param {string}   type           'Directory' or 'File'; used for display purposes.
     * @param {string}   placeholder    Hint text to put in input text area.
     * @param {function} callback       function(path), where path is the path to the new file.
     */
    function fsNewItemPrompt(parent, type, placeholder, callback) {
        var $positiveButton = $_promptModal.find('button.positive');
        var isDir = type == 'Directory';

        $_promptModal.find('#promptModalInput').attr('placeholder', placeholder);
        $_promptModal.find('.modal-title').text('Create ' + type);
        $positiveButton.text('Create');
        $positiveButton.on('click', function() {
            var name = $_promptModal.find('#promptModalInput').val();
            if (isDir) { name = name + '/'; }

            $_promptModal.modal('hide');
            callback(parent + name);
        });

        $_promptModal.modal();
    }

    /**
     * Launch a modal to confirm the user wants to delete a file.
     *
     * @param {string}   path       Path of item to delete.
     * @param {function} callback   function(path), where path is the path to the file to be deleted.
     */
    function fsConfirmDelete(path, callback) {
        var $positiveButton = $_confirmModal.find('button.positive');

        $_confirmModal.find('.modal-title').text('Confirm Delete?');
        $_confirmModal.find('.modal-body').text('Are you sure you want to delete ' + path + '?');

        $positiveButton.on('click', function() {
            $_confirmModal.modal('hide');
            callback(path);
        });

        $_confirmModal.modal();
    }

    /**
     * Wipe the confirm modal clean for the next use.
     */
    function clearConfirmModal() {
        console.log('In clearConfirmModal()');
        var $positiveButton = $_confirmModal.find('button.positive');

        $_promptModal.modal('hide');

        $_confirmModal.find('.modal-title').text('');
        $_confirmModal.find('.modal-body').text('');
        $positiveButton.text('Confirm');
        $positiveButton.unbind('click');
        $_confirmModal.find('button.negative').unbind('click');
    }

    $_confirmModal.on('hidden.bs.modal', function(e) {
        clearConfirmModal()
    });

    /**
     * Wipe the prompt modal clean for the next use.
     */
    function clearPromptModal() {
        console.log('In clearPromptModal()');
        var $modalInput = $_promptModal.find('#promptModalInput');
        var $positiveButton = $_promptModal.find('button.positive');

        $_promptModal.modal('hide');

        $_promptModal.find('.modal-title').text('');
        $modalInput.attr('placeholder', '');
        $modalInput.val('');
        $positiveButton.text('Confirm');
        $positiveButton.unbind('click');
        $_promptModal.find('button.negative').unbind('click');
    }

    $_promptModal.on('hidden.bs.modal', function(e) {
        clearPromptModal()
    });
});

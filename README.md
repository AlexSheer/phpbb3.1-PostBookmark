# phpBB Post Bookmark Extension

This is the repository for the development of the phpBB Post Bookmark Extension.

This extension allows you to create bookmarks to posts. Bookmarks can be controlled in the UCP.
When you post, you can quickly insert a link to a post by selecting it from the list of bookmarks.

Расширение позволяет создавать закладки на сообщения форума. Закладками можно управлять в личном разделе.
При размещении сообщения можно быстро вставить ссылку на сообщение, выбрав его из списка закладок.

## Quick Install
You can install this on the latest copy of the develop branch ([phpBB 3.1-dev](https://github.com/phpbb/phpbb3)) by following the steps below:

1. [Download the latest release](https://github.com/AlexSheer/phpbb3.1-PostBookmark). Распакуйте загруженный архив и переименуйте папку `phpbb3.1-PostBookmark-master` в `postbookmark`.
2. Unzip the downloaded release, and change the name of the folder to `postbookmark`.
3. In the `ext` directory of your phpBB board, create a new directory named `sheer` (if it does not already exist).
4. Copy the `postbookmark` folder to `phpBB/ext/sheer/` .
5. Navigate in the ACP to `Customise -> Manage extensions`.
6. Look for `Post Bookmarks` under the Disabled Extensions list, and click its `Enable` link.

## Uninstall

1. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
2. Look for `Post Bookmarks` under the Enabled Extensions list, and click its `Disable` link.
3. To permanently uninstall, click `Delete Data` and then delete the `/ext/sheer/postbookmark` folder.

## License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

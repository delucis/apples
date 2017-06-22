# apples :green_apple::green_apple::green_apple:

[![GitHub release](https://img.shields.io/github/release/delucis/apples.svg?maxAge=1800)](https://github.com/delucis/apples/releases/latest) [![Build Status](https://travis-ci.org/delucis/apples.svg?branch=master)](https://travis-ci.org/delucis/apples)

Repository for WordPress theme development for <http://claraiannotta.com>.

## Development

### Set-up

1. Use [`apples-wp-dev`](https://github.com/delucis/apples-wp-dev) to set up a development environment & bootstrap dependencies (including this theme repo).

2. Once `apples-wp-dev` is up and running, `cd` to the theme directory:

    ```sh
    cd wordpress/wp-content/themes/apples
    ```

3. Install development dependencies (requires [Node/npm](https://nodejs.org/)):

    ```sh
    npm install
    ```

### Build CSS
- Watch `scss/style.scss` and auto-compile when it changes:

    ```sh
    npm start
    ```

- Build theme, producing a bundled `apples.zip` theme archive:

    ```sh
    npm run build
    ```

That’s it! Using the `apples-wp-dev` environment, a fully functional WordPress install can be found at <http://claraiannotta.dev>. Log in as `admin` with password `vagrant`.

Changes to any of the `php` files can be seen simply by refreshing your browser.

To change `css` styling, edit `scss/style.scss`, which will auto-compile if `npm start` is running.

## Releasing a version

1. Document changes in [`CHANGELOG.md`](CHANGELOG.md).

2. Increment version number in `scss/style.scss` header (and auto-compile a new `style.css`) using [semantic versioning](http://semver.org/).

3. Increment the package version number and tag the commit using `npm version`:

    ```sh
    npm version major # 1.3.2 -> 2.0.0
    npm version minor # 1.3.2 -> 1.4.0
    npm version patch # 1.3.2 -> 1.3.3
    ```

    A default commit message will be set using the version number. To use a custom commit message instead (`%s` can be used to print the version number):

    ```sh
    npm version patch -m "Release %s. Closes #39."
    ```

4. Push your changes including the newly created tag:

    ```sh
    git push --folow-tags
    ```

    After you have pushed your tag, Travis CI will build `apples.zip` and attach it to the GitHub release. This archive is used by automatic theme updates in WordPress.

5. Copy the changes listed in `CHANGELOG.md` to the release notes on GitHub.

## Dependencies

_These are automatically installed if you use the `apples-wp-dev` environment._

- [Twenty Twelve](https://wordpress.org/themes/twentytwelve/) WordPress theme

- Elliot Condon’s [Advanced Custom Fields {v5}](https://www.advancedcustomfields.com/pro/)

## [Changelog](CHANGELOG.md)

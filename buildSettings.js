/**
 * 仮想環境のドメイン
 * 末尾の/はなし
 */
const DOMAIN = 'http://dev-pack.local';

/**
 * 吐き出し先切り替え用
 * 'STATIC' => DIR.DEST
 * 'WP' => DIR.THEMES
 */
const ENV = 'WP';

/**
 * テーマ名とwpのthemesフォルダまでのパスを記載
 */
const themesName = 'tokyo_shibuya_wp';
const themesPath = `../cms/wp-content/themes/${themesName}/`;

/**
 * ディレクトリの設定
 * @param {string} path './' or '' webpackとgulpでpathの記述方法が違うため
 * @return {object} path settings
 */
const directorySettings = path => {
  const basePath = path || '';
  return {
    SRC: basePath + 'app/src/',
    SRC_ASSETS: basePath + 'app/src/assets/',
    DEST: basePath + 'app/dest/',
    DEST_ASSETS: basePath + 'app/dest/assets/',
    THEMES: themesPath,
    THEMES_ASSETS: `${themesPath}assets/`
  };
};

/**
 * style.cssに記載する author情報
 */
const styleComment = '/*\n' + `
Theme Name: ${themesName}
Theme URI:
Author:
Version: 1.0
` + '\n*/';

export {
  DOMAIN,
  ENV,
  directorySettings,
  styleComment
};

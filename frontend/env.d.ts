interface ImportMetaEnv {
    readonly BASE_URL: string;
    readonly VUE_APP_API_URL: string;
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}

const prefixApi = '/api/internal/';

const APIRoutes = {
    nalogRu: {
        getSettings: {
            url: prefixApi + 'settings/nalog-ru',
            title: 'Получение настроек текущего пользователя',
        },
        register: {
            url: prefixApi + 'settings/nalog-ru/register',
            title: 'Запрос на регистрацию',
        },
        restorePassword: {
            url: prefixApi + 'settings/nalog-ru/restore-password',
            title: 'Запрос на восстановления пароля',
        },
        create: {
            url: prefixApi + 'settings/nalog-ru/create',
            title: 'Создание интеграции',
        },
        update: {
            url: prefixApi + 'settings/nalog-ru/update',
            title: 'Обновление интеграции',
        }
    },
};

export default APIRoutes;
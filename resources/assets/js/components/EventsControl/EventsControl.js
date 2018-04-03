import template from './template.html';

export default {
    name: "events-control",
    template: template,
    props: {
        showEventControl: {
            type: Boolean,
            default: false,
        },
    },
    data: () => ({

    }),
}
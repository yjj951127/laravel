export default [
    {
        path: "/hello",
        name: "hello",
        meta: {
            title: 'hello',
        },
        component: resolve => require(['../views/hello'], resolve),
    }
];

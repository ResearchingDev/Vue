export var menuItems = {
    admin: {
        data: [
            {
                headTitle1: "",
                headTitle2: "Dashboards,Widgets & Layout.",
                type: "headtitle",
            },
            {
                path: "/",
                title: "Dashboard",
                icon: "stroke-home",
                iconf: "fill-home",
                type: "link",
                badgeType: "light-primary",
                active: true,
            },
            {
                title: "Manage Clients",
                icon: "stroke-user",
                iconf: "fill-user",
                type: "sub",
                active: false,
                children: [
                    {
                        path: "/admin/client",
                        title: "Clients",
                        type: "link",
                        active: false,
                    },
                ],
            },
        ],
    },
    client: {
        data: [
            {
                headTitle1: "",
                headTitle2: "Dashboards,Widgets & Layout.",
                type: "headtitle",
            },
            {
                path: "/",
                title: "Dashboard",
                icon: "stroke-home",
                iconf: "fill-home",
                type: "link",
                badgeType: "light-primary",
                active: true,
            },
            {
                title: "Manage Users",
                icon: "stroke-user",
                iconf: "fill-user",
                type: "sub",
                active: false,
                children: [
                    {
                        path: "/client/users",
                        title: "Users",
                        type: "link",
                        active: false,
                    },
                ],
            },
            {
                path: "/client/roles",
                title: "Roles",
                icon: "stroke-social",
                iconf:"fill-social",
                type: "link",
                badgeType: "light-primary",
                active: true,
            },
        ],
    },
};

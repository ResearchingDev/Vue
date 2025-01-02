export var menuItems={
  "data": [
    {
      "headTitle1": "",
      "headTitle2": "Dashboards,Widgets & Layout.",
      "type": "headtitle"
    },
    {
      "path": "/",
      "title": "Dashboard",
      "icon": "stroke-home",
      "iconf":"fill-home",
      "type": "link",
      "badgeType": "light-primary",
      "active": true,
    },
    {
      "title": "Users",
      "icon": "stroke-user",
      "iconf":"fill-user",
      "type": "sub",
      "active": false,
      "children": [
        {
          "path": "/users",
          "title": "Users",
          "type": "link",
          "active":false
        },
      ]
    },
  ]
}

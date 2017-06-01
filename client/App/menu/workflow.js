var mainwindow_menu = new nw.Menu({ type: 'menubar' });

var submenu = new nw.Menu();
item = new nw.MenuItem({
    label: 'Регистратура',
    type: "normal",
    icon: "img/icon.png"
});
item.click = open_rmis36();
submenu.append(item);

item = new nw.MenuItem({
    label: 'Портал ДЗ',
    type: "normal",
    icon: "img/icon.png"
});
item.click = open_zdrav36();
submenu.append(item);

item = new nw.MenuItem({
    label: 'Почта ВЭП',
    type: "normal",
    icon: "img/icon.png"
});
item.click = open_mail();
submenu.append(item);

item = new nw.MenuItem({
    label: 'ИПРА',
    type: "normal",
    icon: "img/icon.png"
});
item.click = open_ipra();
submenu.append(item);

// the menu item appended should have a submenu
mainwindow_menu.append(new nw.MenuItem({
    label: 'Ресурсы',
    submenu: submenu
}));
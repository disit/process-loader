INSERT INTO MainMenu (linkUrl, privileges, userType, externalApp, openMode, iconColor, pageTitle, linkId,icon, text)
VALUES('userlimits.php', '{RootAdmin}', 'any', 'no', 'samePage', '#582A72', 'User Limits Editor','User Limits Editor','fa fa-user-plus', 'User Limits Editor');


INSERT INTO functionalities (id,functionality, RootAdmin, ToolAdmin, AreaManager, Manager, Public, link, view, class) VALUES (56,'User Limits Editor',1,0,0,0,0, 'userlimits.php', view, NULL);

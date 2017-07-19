fos.Router.setData({"base_url":"","routes":{"gema_home":{"tokens":[["text","\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"gema_login":{"tokens":[["text","\/login"]],"defaults":[],"requirements":[],"hosttokens":[]},"gema_logout":{"tokens":[["text","\/logout"]],"defaults":[],"requirements":[],"hosttokens":[]},"gema_login_check":{"tokens":[["text","\/login_check"]],"defaults":[],"requirements":[],"hosttokens":[]},"persona":{"tokens":[["text","\/persona\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"persona_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/persona"]],"defaults":[],"requirements":[],"hosttokens":[]},"persona_new":{"tokens":[["text","\/persona\/new"]],"defaults":[],"requirements":[],"hosttokens":[]},"persona_create":{"tokens":[["text","\/persona\/create"]],"defaults":[],"requirements":{"_method":"post"},"hosttokens":[]},"persona_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/persona"]],"defaults":[],"requirements":[],"hosttokens":[]},"persona_update":{"tokens":[["text","\/update"],["variable","\/","[^\/]++","id"],["text","\/persona"]],"defaults":[],"requirements":[],"hosttokens":[]},"persona_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/persona"]],"defaults":[],"requirements":[],"hosttokens":[]},"usuario":{"tokens":[["text","\/usuario\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"usuario_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/usuario"]],"defaults":[],"requirements":[],"hosttokens":[]},"usuario_new":{"tokens":[["text","\/usuario\/new"]],"defaults":[],"requirements":[],"hosttokens":[]},"usuario_create":{"tokens":[["text","\/usuario\/create"]],"defaults":[],"requirements":{"_method":"post"},"hosttokens":[]},"usuario_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/usuario"]],"defaults":[],"requirements":[],"hosttokens":[]},"usuario_update":{"tokens":[["text","\/update"],["variable","\/","[^\/]++","id"],["text","\/usuario"]],"defaults":[],"requirements":[],"hosttokens":[]},"usuario_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/usuario"]],"defaults":[],"requirements":[],"hosttokens":[]},"rol":{"tokens":[["text","\/rol\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"rol_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/rol"]],"defaults":[],"requirements":[],"hosttokens":[]},"rol_new":{"tokens":[["text","\/rol\/new"]],"defaults":[],"requirements":[],"hosttokens":[]},"rol_create":{"tokens":[["text","\/rol\/create"]],"defaults":[],"requirements":{"_method":"post"},"hosttokens":[]},"rol_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/rol"]],"defaults":[],"requirements":[],"hosttokens":[]},"rol_update":{"tokens":[["text","\/update"],["variable","\/","[^\/]++","id"],["text","\/rol"]],"defaults":[],"requirements":[],"hosttokens":[]},"rol_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/rol"]],"defaults":[],"requirements":[],"hosttokens":[]},"actividad":{"tokens":[["text","\/actividad\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"actividad_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/actividad"]],"defaults":[],"requirements":[],"hosttokens":[]},"actividad_new":{"tokens":[["text","\/actividad\/new"]],"defaults":[],"requirements":[],"hosttokens":[]},"actividad_create":{"tokens":[["text","\/actividad\/create"]],"defaults":[],"requirements":{"_method":"post"},"hosttokens":[]},"actividad_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/actividad"]],"defaults":[],"requirements":[],"hosttokens":[]},"actividad_update":{"tokens":[["text","\/update"],["variable","\/","[^\/]++","id"],["text","\/actividad"]],"defaults":[],"requirements":[],"hosttokens":[]},"actividad_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/actividad"]],"defaults":[],"requirements":[],"hosttokens":[]},"activo":{"tokens":[["text","\/activo\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"activo_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/activo"]],"defaults":[],"requirements":[],"hosttokens":[]},"activo_new":{"tokens":[["text","\/activo\/new"]],"defaults":[],"requirements":[],"hosttokens":[]},"activo_create":{"tokens":[["text","\/activo\/create"]],"defaults":[],"requirements":{"_method":"post"},"hosttokens":[]},"activo_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/activo"]],"defaults":[],"requirements":[],"hosttokens":[]},"activo_update":{"tokens":[["text","\/update"],["variable","\/","[^\/]++","id"],["text","\/activo"]],"defaults":[],"requirements":[],"hosttokens":[]},"activo_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/activo"]],"defaults":[],"requirements":[],"hosttokens":[]},"area":{"tokens":[["text","\/area\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"area_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/area"]],"defaults":[],"requirements":[],"hosttokens":[]},"area_new":{"tokens":[["text","\/area\/new"]],"defaults":[],"requirements":[],"hosttokens":[]},"area_create":{"tokens":[["text","\/area\/create"]],"defaults":[],"requirements":{"_method":"post"},"hosttokens":[]},"area_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/area"]],"defaults":[],"requirements":[],"hosttokens":[]},"area_update":{"tokens":[["text","\/update"],["variable","\/","[^\/]++","id"],["text","\/area"]],"defaults":[],"requirements":[],"hosttokens":[]},"area_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/area"]],"defaults":[],"requirements":[],"hosttokens":[]},"factura":{"tokens":[["text","\/factura\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"factura_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/factura"]],"defaults":[],"requirements":[],"hosttokens":[]},"factura_new":{"tokens":[["text","\/factura\/new"]],"defaults":[],"requirements":[],"hosttokens":[]},"factura_create":{"tokens":[["text","\/factura\/create"]],"defaults":[],"requirements":{"_method":"post"},"hosttokens":[]},"factura_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/factura"]],"defaults":[],"requirements":[],"hosttokens":[]},"factura_update":{"tokens":[["text","\/update"],["variable","\/","[^\/]++","id"],["text","\/factura"]],"defaults":[],"requirements":[],"hosttokens":[]},"factura_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/factura"]],"defaults":[],"requirements":[],"hosttokens":[]},"ordentrabajo":{"tokens":[["text","\/ordentrabajo\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"ordentrabajo_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/ordentrabajo"]],"defaults":[],"requirements":[],"hosttokens":[]},"ordentrabajo_new":{"tokens":[["text","\/ordentrabajo\/new"]],"defaults":[],"requirements":[],"hosttokens":[]},"ordentrabajo_create":{"tokens":[["text","\/ordentrabajo\/create"]],"defaults":[],"requirements":{"_method":"post"},"hosttokens":[]},"ordentrabajo_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/ordentrabajo"]],"defaults":[],"requirements":[],"hosttokens":[]},"ordentrabajo_update":{"tokens":[["text","\/update"],["variable","\/","[^\/]++","id"],["text","\/ordentrabajo"]],"defaults":[],"requirements":[],"hosttokens":[]},"ordentrabajo_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/ordentrabajo"]],"defaults":[],"requirements":[],"hosttokens":[]},"planmtto":{"tokens":[["text","\/planmtto\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"planmtto_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/planmtto"]],"defaults":[],"requirements":[],"hosttokens":[]},"planmtto_new":{"tokens":[["text","\/planmtto\/new"]],"defaults":[],"requirements":[],"hosttokens":[]},"planmtto_create":{"tokens":[["text","\/planmtto\/create"]],"defaults":[],"requirements":{"_method":"post"},"hosttokens":[]},"planmtto_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/planmtto"]],"defaults":[],"requirements":[],"hosttokens":[]},"planmtto_update":{"tokens":[["text","\/update"],["variable","\/","[^\/]++","id"],["text","\/planmtto"]],"defaults":[],"requirements":[],"hosttokens":[]},"planmtto_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/planmtto"]],"defaults":[],"requirements":[],"hosttokens":[]},"proveedor":{"tokens":[["text","\/proveedor\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"proveedor_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/proveedor"]],"defaults":[],"requirements":[],"hosttokens":[]},"proveedor_new":{"tokens":[["text","\/proveedor\/new"]],"defaults":[],"requirements":[],"hosttokens":[]},"proveedor_create":{"tokens":[["text","\/proveedor\/create"]],"defaults":[],"requirements":{"_method":"post"},"hosttokens":[]},"proveedor_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/proveedor"]],"defaults":[],"requirements":[],"hosttokens":[]},"proveedor_update":{"tokens":[["text","\/update"],["variable","\/","[^\/]++","id"],["text","\/proveedor"]],"defaults":[],"requirements":[],"hosttokens":[]},"proveedor_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/proveedor"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoactividad":{"tokens":[["text","\/tipoactividad\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoactividad_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/tipoactividad"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoactividad_new":{"tokens":[["text","\/tipoactividad\/new"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoactividad_create":{"tokens":[["text","\/tipoactividad\/create"]],"defaults":[],"requirements":{"_method":"post"},"hosttokens":[]},"tipoactividad_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/tipoactividad"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoactividad_update":{"tokens":[["text","\/update"],["variable","\/","[^\/]++","id"],["text","\/tipoactividad"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoactividad_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/tipoactividad"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoactivo":{"tokens":[["text","\/tipoactivo\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoactivo_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/tipoactivo"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoactivo_new":{"tokens":[["text","\/tipoactivo\/new"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoactivo_create":{"tokens":[["text","\/tipoactivo\/create"]],"defaults":[],"requirements":{"_method":"post"},"hosttokens":[]},"tipoactivo_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/tipoactivo"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoactivo_update":{"tokens":[["text","\/update"],["variable","\/","[^\/]++","id"],["text","\/tipoactivo"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoactivo_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/tipoactivo"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoprioridad":{"tokens":[["text","\/tipoprioridad\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoprioridad_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/tipoprioridad"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoprioridad_new":{"tokens":[["text","\/tipoprioridad\/new"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoprioridad_create":{"tokens":[["text","\/tipoprioridad\/create"]],"defaults":[],"requirements":{"_method":"post"},"hosttokens":[]},"tipoprioridad_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/tipoprioridad"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoprioridad_update":{"tokens":[["text","\/update"],["variable","\/","[^\/]++","id"],["text","\/tipoprioridad"]],"defaults":[],"requirements":[],"hosttokens":[]},"tipoprioridad_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/tipoprioridad"]],"defaults":[],"requirements":[],"hosttokens":[]},"traza":{"tokens":[["text","\/traza\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"traza_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/traza"]],"defaults":[],"requirements":[],"hosttokens":[]}},"prefix":"","host":"localhost","scheme":"http"});
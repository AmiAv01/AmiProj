export let starterParts = new Map([["Вилки стартера", "/catalog/generator_parts/rectifier"],
    ["Втулки стартера", "/catalog/starter_parts/sleeve"], ["Втягивающие реле", "/catalog/starter_parts/relay"],
    ["Детали втягивающего реле", "/catalog/starter_parts/relay_parts"],
    ["Детали привода стартера", "/catalog/starter_parts/unit_parts"], ["Крышки и головы", "/catalog/starter_parts/cover"],
    ["Приводы", "/catalog/starter_parts/unit"], ["Прочее для стартера", "/catalog/starter_parts/other"],
    ["Редукторы и компоненты", "/catalog/starter_parts/reducer"], ["Ремкомплекты", "/catalog/starter_parts/repair"],
    ["Сальники стартера", "/catalog/starter_parts/glands"], ["Статоры", "/catalog/starter_parts/stator"],
    ["Щётки стартера", "/catalog/starter_parts/brush"], ["Щёткодержатели стартера", "/catalog/starter_parts/holder"],
    ["Якоря", "/catalog/starter_parts/anchor"]]);

export let generatorParts = new Map([["Втулки генератора", "/catalog/generator_parts/sleeve"],
    ["Вакуумные насосы", "/catalog/generator_parts/pump"], ["Выпрямители", "/catalog/generator_parts/rectifier"],
    ["Диоды и триоды", "/catalog/generator_parts/diode"], ["Изоляторы", "/catalog/generator_parts/insulator"],
    ["Крышки", "/catalog/generator_parts/cover"], ["Регуляторы", "/catalog/generator_parts/regulator"],
    ["Роторы", "/catalog/generator_parts/rotor"], ["Терминалы", "/catalog/generator_parts/terminal"],
    ["Сальники", "/catalog/generator_parts/gland"], ["Статорные обмотки", "/catalog/generator_parts/winding"],
    ["Шкивы", "/catalog/generator_parts/pulley"], ["Щётки генератора", "/catalog/generator_parts/brush"],
    ["Щёточные узлы", "/catalog/generator_parts/holder"]]);

export let otherParts = new Map([["Стартеры", "/catalog/starters"], ["Генераторы", '/catalog/generators'],
    ["Подшипники", '/catalog/bearings'], ["Прочие запчасти", "/catalog/other"], ["Вилки стартера", "/catalog/generator_parts/rectifier"],
    ["Втулки стартера", "/catalog/starter_parts/sleeve"], ["Втягивающие реле", "/catalog/starter_parts/relay"],
    ["Детали втягивающего реле", "/catalog/starter_parts/relay_parts"],
    ["Детали привода стартера", "/catalog/starter_parts/unit_parts"], ["Крышки и головы", "/catalog/starter_parts/cover"],
    ["Приводы", "/catalog/starter_parts/unit"], ["Прочее для стартера", "/catalog/starter_parts/other"],
    ["Редукторы и компоненты", "/catalog/starter_parts/reducer"], ["Ремкомплекты", "/catalog/starter_parts/repair"],
    ["Сальники стартера", "/catalog/starter_parts/glands"], ["Статоры", "/catalog/starter_parts/stator"],
    ["Щётки стартера", "/catalog/starter_parts/brush"], ["Щёткодержатели стартера", "/catalog/starter_parts/holder"],
    ["Якоря", "/catalog/starter_parts/anchor"], ["Втулки генератора", "/catalog/generator_parts/sleeve"],
    ["Вакуумные насосы", "/catalog/generator_parts/pump"], ["Выпрямители", "/catalog/generator_parts/rectifier"],
    ["Диоды и триоды", "/catalog/generator_parts/diode"], ["Изоляторы", "/catalog/generator_parts/insulator"],
    ["Крышки", "/catalog/generator_parts/cover"], ["Регуляторы", "/catalog/generator_parts/regulator"],
    ["Роторы", "/catalog/generator_parts/rotor"], ["Терминалы", "/catalog/generator_parts/terminal"],
    ["Сальники", "/catalog/generator_parts/gland"], ["Статорные обмотки", "/catalog/generator_parts/winding"],
    ["Шкивы", "/catalog/generator_parts/pulley"], ["Щётки генератора", "/catalog/generator_parts/brush"],
    ["Щёточные узлы", "/catalog/generator_parts/holder"]]);

export const menuItems = new Map([['Новости', '/news'], ['О нас', '/info'], ['Скачать', '/desktop']]);
export const subCategories = new Map([['Генераторы', '/catalog/generators'], ['Стартеры', '/catalog/starters'],
    ['Подшипники', '/catalog/bearings'], ['Прочее', '/catalog/other']]);

export const adminMenuItems = new Map([
    ['Запчасти', 'admin.details.index'],
    ['Заказы', 'admin.orders.index'],
    ['Новости', 'admin.news.index'],
    ['Пользователи', 'admin.users.index'],
    ['Курс валют', 'admin.currency.index']]);

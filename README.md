# AQUI TIENES LOS PASOS PARA EJECUTAR ESTE PROYECTO

## HERRAMIENTAS
- Necesitas tener un servidor local instalado, preferentemente **XAMPP**

## CREACION DE LA BASE DE DATOS
- Debes crear la base de datos necesaria
- El script se encuentra en el archivo `script.mysql`

## VARIABLES DE ENTORNO
1. Localiza el archivo `example.env`
2. Crea una copia y renómbrala a `.env`
3. Colócalo al mismo nivel que `example.env`
4. Rellena los campos faltantes:
    ```env
    DB_HOST= 'TU HOSTNAME'
    DB_USER= 'TU USUARIO'
    DB_PASSWORD= 'TU PASSWORD'
    DB_NAME=mvc
    ```
    > Nota: El nombre de la DB debe coincidir con el del script ejecutado

## PASO FINAL 
1. Ejecuta el servidor local
2. Abre tu navegador
3. Accede a la siguiente ruta por defecto:
    ```
    http://localhost/CRUD/index.html
    ```

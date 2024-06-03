# KIWI
Es un sistema de gestion de cola de procesos.
Esta pensado para funcionar como un paquete autogestionado. 
Sirve para crear procesos encolados, guardar los parametros necesarios y ejecutar la cola.

## Instalación

Se debe clonar el paquete desde aqui [cd-queue](https://github.com/aridupuy/kiwi/)

    git clone git@github.com:aridupuy/kiwi.git
    
o se puede tambien agregar a composer
* Primero se debe agregar al archivo composer.json del proyecto 
    ```json
      "require": {
            "adupuy/cd-queue": "master"
        },
      "repositories": [
          {
            "type": "vcs",
            "url": "git@github.com:aridupuy/kiwi.git^"
          }
    ]
  ```
  * luego ejecutamos 
  ```bash
  composer require adupuy/cd-queue
  ```
  * Si se tienen problemas con los accesos composer proporciona un archivo auth.json. Puedes crear o modificar este archivo en el directorio ~/.composer/
   ```json
   {
        "http-basic": {
            "github.com": {
                "username": "tu_usuario",
                "password": "tu_contraseña"
            }
        }
    }
    ```
## Uso

Luego de la instalacion se deben generar objetos 'workers' que se van a utilizar.
### Los Workers deben extender de la clase AbstractWorker proporcionada en el paquete.
Con esto definimos y agregamos al manejador de la cola que worker procesará que mensaje
* Para incluir un nuevo worker se debe llamar al metodo **Queue::registerWorker** se debe proporcionar un name y un objeto 'worker'

* Para agregar una nueva tarea a la cola se debe llamar al metodo **Queue::add** se debe proporcionar un nombre para la tarea, que debe coincidir con un worker,

* en un proceso a parte manejado por el usuario, puede ser manual o con un crontab, 
 se debe llamar al metodo **Queue::run**, no necesita parametros, la cola comenzara a categorizar los jobs entre los workers y ejecutara de manera sincronica cada uno.
en caso de que un job no tenga worker asignado sera ignorado hasta la proxima ejecucion.


## Contribución

* [Ariel Dupuy](https://git.cobrodigital.com/adupuy)

## Licencia

Este proyecto es propiedad de CobroDigital y está licenciado bajo [Licencia MIT](https://opensource.org/licenses/MIT) - consulta el archivo [LICENSE](LICENSE) para más detalles.



# Coders


### Dependencies ###

* make
* docker
* docker-compose

1- Go to App Docker folder.
```sh
$ cd appdock/
```

2- Build it using make
```sh
appdock$ make rebuild
```
* It could take a while be patient.

3- Get into the app container and run these commands
```sh
appdock$ make root
/app# apt-get install mysql-server -y
```
* Set whatever user and pass when installing mysql, actually it wont be used however it is needed for running `mysql` command.

4- Update local hosts file.
Copy the inet addr and exit from the app docker container.
```sh
/app# ifconfig
/app# exit
```
Copy the ip address i.e 192.168.32.3 and open the hosts file.
```sh
appdock$ sudo vi /etc/hosts
```
Modify hosts file.
```sh
...
192.168.32.3    coders.local

```
save and exit.

5- Install CMS and upload Coders CMS configuration.
```sh
appdock$ make cms-install; make cms-update
```
6- Verify the installation. When this process is complete, you should have a working drupal site by navigating to [http://coders.local:8000/](http://coders.local:8000/)


### OpenStreetMap ###
In order to display locations on a OpenStreetMap, create Locations nodes with latitude/longitude




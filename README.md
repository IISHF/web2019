# web2019
The relaunch of www.iishf.com

## VirtualBox Setup

The project contains a VirtualBox setup using Vagrant with Ansible 
provisioning. Just run `vagrant up` inside the `./vagrant` folder.
[VirtualBox][1], [Vagrant][2] and [Ansible][3] need to be installed 
on the host.

The Nginx webserver running on the virtual machine is configured to
use HTTP/2 and therefore requires SSL certificates. **Before running 
the Vagrant box for the first time ensure that the appropriate 
certificates have been created.** The *Common Name* for the 
certificate must be set to `iishf.test`. The script 
`./vagrant/roles/webserver/files/mkcert.sh` can be used to create an
appropriate key/certificate pair.

The virtual machine exposes HTTPS on port `4430` and the MySQL 
database on port `33060`. The project directory is mounted 
into the virtual machine to `/var/www/iishf` using NFS.

[1]: https://www.virtualbox.org/wiki/Downloads
[2]: https://www.vagrantup.com/downloads.html
[3]: https://en.wikipedia.org/wiki/Ansible_(software)

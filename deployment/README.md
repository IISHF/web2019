Ubuntu 16.04
---

- ubuntu/images/hvm-ssd/ubuntu-bionic-18.04-amd64-server-20200112 (ami-0b418580298265d5c)
- Run on terminal prior to provisioning
    ```
    sudo apt-get update
    sudo apt-get install -y python-simplejson
    ```
- Or add to *User Data*
    ```
    #!/usr/bin/env bash
    apt-get update
    apt-get install -y python-simplejson
    ```

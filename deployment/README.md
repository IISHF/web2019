Ubuntu 20.04
---

- ubuntu/images/hvm-ssd/ubuntu-focal-20.04-amd64-server-20200729 (ami-0c9beb525411868a1)
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

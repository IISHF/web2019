Certbot commands
===

iishf.com
---

```
sudo certbot run \
    --authenticator standalone \
    --installer nginx \
    --no-redirect \
    --email <<your email address>> \
    --agree-tos \
    --non-interactive \
    --domains public.iishf.com \
    --domains beta.iishf.com \
    --domains dev.iishf.com 
```    

```
sudo certbot renew \
    --installer none \
    --pre-hook "service nginx stop" \
    --post-hook "service nginx start" \
    --non-interactive
```    

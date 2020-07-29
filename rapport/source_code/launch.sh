#!/bin/bash
setfacl -m user:1000:r ${HOME}/.Xauthority

if [ "$(docker images -q test:latest) != "" " ]; then
echo "Image is already built"
else
    docker build -t test .
fi

docker run \
    -it \
    --rm \
    --name test \
    --network="host" \
    -e DISPLAY \
    -v ${HOME}/.Xauthority:/home/user/.Xauthority \
    -v ${HOME}/logisim/data:/home/user/data
    test

#!/bin/bash
setfacl -m user:1000:r ${HOME}/.Xauthority

IMAGE="$(docker images -q logisim:latest)"

if [ -z "$IMAGE" ]; then
    docker build -t logisim .
fi
xhost + "local:docker@"
docker run -it --rm --network=host -e DISPLAY -v ${HOME}/.Xauthority:/home/user/.Xauthority -v ${HOME}/logisim/data:/home/user/data logisim
xhost - "local:docker@"

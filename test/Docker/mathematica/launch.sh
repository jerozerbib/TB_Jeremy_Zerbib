#!/bin/bash
setfacl -m user:1000:r ${HOME}/.Xauthority

IMAGE="$(docker images -q mathematica:latest)"

if [ -n "$IMAGE" ]; then
    echo "Image is already built"
else
    docker build -t mathematica .
fi

xhost + "local:docker@"
docker run \
    -it \
    --rm \
    --network=host \
    -e DISPLAY \
    -v ${HOME}/.Xauthority:/home/user/.Xauthority \
    -v ${HOME}/mathematica/data:/home/user/data \
    mathematica

xhost - "local:docker@"

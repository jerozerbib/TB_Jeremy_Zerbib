FROM ubuntu:bionic

# Do not erase this part of the config !!!
ENV TZ=Europe/Zurich
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install all dependencies
RUN apt-get update && apt-get -y upgrade 
RUN apt-get -y install sudo

RUN rm -rf /tmp/* /usr/share/doc/* /usr/share/info/* /var/tmp/*
RUN useradd -m user && echo "user:user" | chpasswd && adduser user sudo
ENV DISPLAY :0

COPY Files /home/user/files
COPY Installer /home/user/installer

RUN chown user:user /home/user/installer && chown user:user /home/user/files

RUN sudo apt-get -y install xz-utils libasound2 libgl1-mesa-glx libfontconfig avahi-daemon qt5-default
RUN sudo service avahi-daemon start
RUN /home/user/installer/MathInstaller -auto

USER user

CMD /usr/local/bin/mathematica

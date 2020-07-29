FROM ubuntu:bionic

# Do not erase this part of the config !!!
RUN apt-get update 
RUN apt-get -y install sudo

RUN rm -rf /tmp/* /usr/share/doc/* /usr/share/info/* /var/tmp/*
RUN useradd -m user && echo "user:user" | chpasswd && adduser user sudo
ENV DISPLAY :0

COPY logisim-evolution.t.jar /home/user/logisim-evolution.t.jar 
RUN chown user:user /home/user/logisim-evolution.t.jar
RUN apt-get -y install default-jre default-jdk

USER user

CMD ["java", "-jar", "/home/user/logisim-evolution.t.jar"]

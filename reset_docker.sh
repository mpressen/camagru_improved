docker container rm -f $(docker container ls -a -q)
docker image rm -f $(docker image ls -q)
docker volume rm $(docker volume ls -q)
docker network rm $(docker network ls -q -f name=camagru_default)
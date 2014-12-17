# sets PATH environment variable to for this project
# usage:
#       source profile
#       . profile

root=$(dirname $(pwd)/$BASH_SOURCE)
toAdd=$root/vendor/bin:$root/node_modules/.bin;
if [[ $PATH == *$toAdd* ]]
then
  echo PATH is already set
fi

export PATH=$toAdd:$PATH
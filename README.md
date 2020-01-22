To be able to run this repository, please download and install PHP Storm along with the latest version of PHP.
Open the repository using PHPStorm and create a Built-In Webserver (via phpStorm) and select the PHP interpreter.
Finally select the root of the document and press the run button. This should host the program on your localhost.
You can access the website through the default port 80 unless you have specified another.



# Hack Camp

This is the central repository for our Hack Camp project.

If you're using IntelliJ, it has a built in SVN manager that integrates with GitHub quite well. You can also use Git on it's own, you will just need to push your local repository to the remote one (this one).

## Resources
There are plenty of tutorials online so please learn how to use Git and GitHub properly so you don't fuck anything up (I'm looking at you Ifty...)

https://learngitbranching.js.org/

https://guides.github.com/introduction/git-handbook/

https://github.github.com/training-kit/downloads/github-git-cheat-sheet.pdf

If you're using Ubuntu or any Linux OS based on Debian, Git may already be installed, if not, use:

    $ sudo apt update
    $ sudo apt install git

If you are on Windows, you can download it from [here.](https://git-scm.com/download/win)


## Basic Git Command Reference
*clone* - copy repository from remote to local, will also set the remote to the original source (this one) so you can pull from it

    $ git clone <git url>

----
*status* - Check the status of files, staging area, changes since last commit etc.

    $ git status

----
*add* - Adds file/s to the staging area, so that they can be commited. 

    $ git add file1.txt file2.txt
    $ git add *.txt
    $ git add .    # adds all files in current directory

----
*commit* - This is actually going to save the *snapshot*. You need to add a commit message. Be as detailed as you can.

    $ git commit    # Will open up your default text editor so you can write a commit message
    $ git commit -m "This is a commit message"    # Add the -m argument to add the commit message in the command, so you don't have to open a text editor.

----
*pull* - Use pull if the remote (this one) has changed and you want those changes on your local repository

    $ git pull <remote url> <branch>

----
*push* - Use if you have added and committed changes in your local repository that you want to push on to the remote repository (this one)

    $ git push <remote url> <branch>

----
*checkout* - Switch to different branches

    $ git checkout <branch name>
    $ git checkout -b <branch name>    # The -b flag will create a new branch and then switch to it

----
## Will be updated


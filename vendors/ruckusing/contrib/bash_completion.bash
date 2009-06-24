# basic bash completion for ruckusing
# k at isr.hu
_ruckusing() 
{
    local cur prev opts
    COMP_WORDBREAKS=${COMP_WORDBREAKS//:}
    COMPREPLY=()
    cur="${COMP_WORDS[COMP_CWORD]}"
    prev="${COMP_WORDS[COMP_CWORD-1]}"
    opts="db:migrate db:schema db:setup db:version db:setversion"

    COMPREPLY=( $(compgen -W "${opts}" -- ${cur}) )
}

complete -F _ruckusing php main.php 

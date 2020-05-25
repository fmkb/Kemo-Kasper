using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class AudioManager : MonoBehaviour
{
    public AudioSource killCellSource, getBonusSource, timeRunningOutSource,
                       roundFinishedSource, countingScoreSource, bonusScoreAppearSource, kasperButtonAppearSource,
                       kasperAppearSource, kasperSource;

    public AudioClip   killCellSound, getBonusSound,
                       roundFinishedSound, bonusScoreAppearSound, kasperButtonAppearSound,
                       kasperAppearSound, kasperSplashSound, kasperJumpSound;



    private void Start()
    {
        countingScoreSource.Stop();
        timeRunningOutSource.Stop();
    }

    public void PlayKillCellSound()
    {
        killCellSource.PlayOneShot(killCellSound);
    }
    public void PlayGetBonusSound()
    {
        getBonusSource.PlayOneShot(getBonusSound);
    }
    public void PlayTimeRuningOutSoundOn()
    {
        timeRunningOutSource.Play();
    }
    public void PlayTimeRuningOutSoundOff()
    {
        timeRunningOutSource.Stop();
    }
    public void PlayRoundFinishedSound()
    {
        roundFinishedSource.PlayOneShot(roundFinishedSound);
    }
    public void PlayCountingScoreSoundOn()
    {
        countingScoreSource.Play();
    }
    public void PlayCountingScoreSoundOff()
    {
        countingScoreSource.Stop();
    }
    public void PlayBonusScoreAppearSound()
    {
        bonusScoreAppearSource.PlayOneShot(bonusScoreAppearSound);
    }
    public void PlayKasperButtonAppearSound()
    {
       kasperButtonAppearSource.PlayOneShot(kasperButtonAppearSound);
    }
    public void PlayKasperAppearSound()
    {
        kasperAppearSource.PlayOneShot(kasperAppearSound);
    }
    public void PlayKasperJumpSound()
    {
        kasperSource.PlayOneShot(kasperJumpSound);
    }
    public void PlayKasperSplashSound()
    {
        kasperSource.PlayOneShot(kasperSplashSound);
    }
}
